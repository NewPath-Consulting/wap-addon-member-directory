<?php

use PO\services\WAService;
use PO\services\SettingsService;
use PO\classes\ContactsUtils;
use PO\classes\Contacts;
use PO\classes\ContactsListingPersistor;

class ContactsAPI
{
    const DEFAULT_CSS_CLASS = "wa-contacts";
    const DEFAULT_CONTACTS_FILTERS = "WA-Contacts-Filter";

    public function __construct()
    {
        $this->register_shortcodes();
        $this->registerJSFile();
        $this->registerRESTRoutes();
    }

    private function registerJSFile() {
        add_action('init', function() {
            wp_register_script('wafw', plugins_url('/js/wafw.js', __FILE__ ), true);
            wp_register_script('wawp_pagination', plugins_url('/js/pagination.js', __FILE__), array('jquery'),true);
            wp_register_script('pagination_plugin', plugins_url('js/pagination.min.js', __FILE__), array('jquery'), true);
        });

    }

    private function registerRESTRoutes() {
        add_action('rest_api_init', function () {
            register_rest_route('wafw/v1', '/contacts/search/', array(
              'methods' => 'GET',
              'callback' => array($this, 'contactsRestRoutes'),
              'permissions_callback' => '__return_true'
            ));

            register_rest_route('wawp/v1', '/contacts/fields/', array(
                'methods' => 'GET',
                'callback' => array($this, 'contactFieldsRestRoute'),
                'permissions_callback' => '__return_true'
            ));

            register_rest_route('wawp/v1', '/savedsearches/', array(
                'methods' => 'GET',
                'callback' => array($this, 'savedSearchesRestRoute'),
                'permissions_callback' => '__return_true'
            ));
        });
    }

    public function contactsRestRoutes($request) {
        $persistedData = ContactsListingPersistor::getPersistedData($request['search-id']);

        if (empty($persistedData)) {
          return array('results' => ['We did not find any results.'], 'errors' => []);
        }

        $sites = $persistedData['sites'];
        $filter = isset($persistedData['filter']) ? $persistedData['filter'] : "" ;
        $profileURL = isset($persistedData['profile-url']) ? $persistedData['profile-url'] : "";
        $args = $persistedData['args'];

        $searchTerm = $request['q'];
        $site = $request['site'];

        if (isset($site) && in_array($site, $sites)) {
            $sites = array($site);
        }

        $select = ContactsUtils::generateSelectStatement($args);

        $contacts = $this->getContactsFromAPI($sites, $filter, $select);
        $contacts = $this->filterContacts($contacts, $args);

        $contacts = $this->searchContactsByKeywords($contacts, $searchTerm);

        $customOutput = apply_filters(
            self::DEFAULT_CONTACTS_FILTERS,
            $contacts
        );

        if ($customOutput !== $contacts) {
            return array('results' => [$customOutput], 'errors' => []);
        }

        ob_start();
        foreach ($contacts as $contact) {
            $this->renderContactDiv($contact, $profileURL);
        }
        $contactsHTML = ob_get_contents();
        ob_end_clean();

        return array('results' => [$contactsHTML], 'errors' => []);;
    }

    public function contactFieldsRestRoute() {
        // $waAPIKeys = SettingsService::getWAapiKeys();

        // $outer_idx = array_key_first($waAPIKeys);

        // $key = $waAPIKeys[$outer_idx]['key'];

        // $waService = new WAService($key);

        // $waService->init();

        $waService = new WAService();

        $data = $waService->getContactFields();

        $response = new WP_REST_Response($data, 200);

        // Set headers.
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        return $response;
    }

    public function savedSearchesRestRoute() {
        // $waAPIKeys = SettingsService::getWAapiKeys();

        // $outer_idx = array_key_first($waAPIKeys);

        // $key = $waAPIKeys[$outer_idx]['key'];

        // $waService = new WAService($key);

        // $waService->init();

        $waService = new WAService();

        $data = $waService->getSavedSearches();

        $response = new WP_REST_Response($data, 200);

        // Set headers.
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        return $response;
    }

    private function register_shortcodes()
    {
        add_shortcode("wa-contacts", array($this, "waContactsShortcode"));
    }

    public function waContactsShortcode($args, $content = null)
    {
        if (!is_array($args)) {
            error_log("wa-contacts not configured properly");
            return "";
        }

        $content = $this->cleanupContent($content);

        $cssClass = $this->extractAndRemoveCSSClass($args);
        $cssClass = $cssClass ?: self::DEFAULT_CSS_CLASS;
        $searchBox = $this->extractAndRemoveSearchToggle($args);
        $sites = $this->extractAndRemoveSites($args);

        $profileURL = $this->extractAndRemoveProfileURL($args);

        $pageSize = $this->extractAndRemovePageSize($args);

        $savedSearch = $this->extractAndRemoveSavedSearch($args);

        $dropdownList = "";
        if ($this->extractAndRemoveDropdown($args)) {
            $dropdownList = $sites;
        }

        $filter = ContactsUtils::generateFilterStatement($content);
        $select = ContactsUtils::generateSelectStatement($args);

        $contactQuery = new ContactsListingPersistor($sites, $profileURL, $filter, $args);
        $queryHash = $contactQuery->save();

        $contacts = $this->getContactsFromAPI($sites, $filter, $select);
        // do_action('qm/debug', $contacts);
        if (!empty($savedSearch)) {
            $contacts = $this->filterContactsWithSavedSearch($contacts, $savedSearch);
        }
        
        $contacts = $this->filterContacts($contacts, $args);

        $customOutput = apply_filters(
            self::DEFAULT_CONTACTS_FILTERS,
            $contacts
        );

        if ($customOutput !== $contacts) {
            return $customOutput;
        }

        return $this->render($contacts, $cssClass, $searchBox, $profileURL, $pageSize, $queryHash, $dropdownList);
    }

    public function render($contacts, $cssClass, $searchBox, $profileURL, $pageSize, 
        $queryHash, $dropdownList)
    {
        
        $pageSizeNum = (int)$pageSize;
        $pagination = count($contacts) > $pageSizeNum;

        ob_start();
        echo "<div class=\"$cssClass\">";

        if ($searchBox) {
            wp_enqueue_script('wafw');
            $this->renderSearchbox($queryHash);
        }

        if (!empty($dropdownList)) {
            $this->renderDropdown($dropdownList);
        }

        echo '<div class="wa-contacts-items">';

        // check to see if pagination needs to happen (> 50 contacts)
        if ($pagination) {
            wp_enqueue_script('pagination_plugin');
            wp_enqueue_script('wawp_pagination');
            wp_localize_script('wawp_pagination', 'wawp_memdir_page_size', array('page_size' => $pageSizeNum));
        }

        foreach ($contacts as $contact) {
            // $this->renderFieldValuesList($contact, $profileURL);
            $this->renderContactDiv($contact, $profileURL);
        }

        echo '</div>';
        if ($pagination) {
            echo '<div class="wa-pagination"></div>';
        }
        echo "</div>";

        return ob_get_clean();
    }

    private function renderContactDiv($fields, $profileURL="") {
        if (empty($fields)) {
            return;
        }

        echo '<div class="wa-contact">';
        foreach ($fields as $field) {
            if (!isset($field['Value'])) {
                continue;
            }

            if (ContactsUtils::isPicture($field['Value'])) {
                //data:image/gif;base64,
                $picture = $this->getPictureFromAPI($field['Value']['Url']);
                $imgType = ContactsUtils::getPictureType($field['Value']['Id']);
                echo "<img src=\"data:image/${imgType};base64,${picture}\"/>";
            } else if (is_array($field['Value'])) {
                $this->renderNestedFieldValuesList($field['FieldName'], $field['Value']);
            } else if ($field['Value'] === "") {
                continue;
            } else {
                $this->renderDivTag(
                    array(
                        "class" => sanitize_title_with_dashes($field['FieldName']),
                        "data-wa-label" => htmlspecialchars($field['FieldName'])
                    ),
                    htmlspecialchars(trim(var_export($field['Value'], true), '\''))
                );
            }
        }

        if ($profileURL) {
            $profileURL = rtrim($profileURL, "/");

            $userID = $fields['Id'];
            echo "<a class=\"wa-more-info\" href=\"/${profileURL}?user-id=${userID}\">More info</a>";
        }

        echo '</div>';
    }

    private function renderFieldValuesList($fields, $profileURL="")
    {
        if (empty($fields)) {
            return;
        }
        echo '<ul class="wa-contact">';
        foreach ($fields as $field) {
            if (!isset($field['Value'])) {
                continue;
            }

            if (ContactsUtils::isPicture($field['Value'])) {

            } else if (is_array($field['Value'])) {
                $this->renderNestedFieldValuesList($field['FieldName'], $field['Value']);
            } elseif ($field['Value'] === "") {
                $this->renderLITag(
                    array(
                        "class" => sanitize_title_with_dashes($field['FieldName']) . " empty",
                        "data-wa-label" => htmlspecialchars($field['FieldName'])
                    )
                );
            } else {
                $this->renderLITag(
                    array(
                        "class" => sanitize_title_with_dashes($field['FieldName']),
                        "data-wa-label" => htmlspecialchars($field['FieldName'])
                    ),
                    htmlspecialchars(trim(var_export($field['Value'], true), '\''))
                );
            }
        }

        if ($profileURL) {
            $profileURL = rtrim($profileURL, "/");

            $userID = $fields['Id'];
            echo "<a class=\"wa-more-info\" href=\"/${profileURL}?user-id=${userID}\">More info</a>";
        }

        echo '</ul>';
    }

    private function renderNestedFieldValuesList($fieldName, $fields)
    {
        if (empty($fields) || !is_array($fields)) {
            return;
        }

        if (isset($fields["Id"])) {
            $liContent = isset($fields['Label']) ? htmlspecialchars($fields['Label']) : "";
        } else {
            $liContent = '<ul>';
            foreach ($fields as $field) {
                $liContent .= '<li>' . htmlspecialchars($field['Label']) . '</li>';
            }
            $liContent.= '</ul>';
        }

        $this->renderLITag(array("class" => sanitize_title_with_dashes($fieldName), "data-wa-label" => $fieldName), $liContent);

    }

    private function renderLITag($attrs, $value="") {
        $attr = array_reduce(array_keys($attrs), function($carry, $key) use ($attrs) {
            return $carry . ' ' . $key . '="' . htmlspecialchars($attrs[$key]) . '"';
        });
        echo "<li $attr>" . $value . '</li>';
    }

    private function renderDivTag($attrs, $value="") {
        $attr = array_reduce(array_keys($attrs), function($carry, $key) use ($attrs) {
            return $carry . ' ' . $key . '="' . htmlspecialchars($attrs[$key]) . '"';
        });
        echo "<div $attr>" . $value . '</div>';
    }

    private function renderSearchbox($searchId="") {
        $searchBoxName = 'wa-contacts-search';
        echo "<form class=\"$searchBoxName\" data-search-id=$searchId>";
        echo "<input type=\"text\" name=\"$searchBoxName\">";
        echo '<input type="submit" name="submit" value="Search">';
        echo "</form>";
    }

    private function renderDropdown($sites) {

        if(empty($sites) || sizeof($sites) <= 1) {
            return;
        }

        $sites = array_merge(array("Show All"), $sites);

        $selectOptions = array_map(function($option) {
            return "<option>$option</option>";
        }, $sites);

        echo '<select class="sites">';
        echo implode("\n", $selectOptions);
        echo "</select>";
    }

    private function cleanupContent($content)
    {
        $content = strip_tags($content);
        $content = trim($content);
        $content = $this->convertContentToLineArray($content);

        return $content;
    }

    private function convertContentToLineArray($content)
    {
        return array_map('trim', explode(PHP_EOL, $content));
    }

    private function extractAndRemoveCSSClass(&$shortCodeArgs)
    {
        $cssClass = "";
        if(isset($shortCodeArgs['class'])) {
            $cssClass = $shortCodeArgs['class'];
            unset($shortCodeArgs['class']);
        }

        return $cssClass;
    }

    private function extractAndRemoveProfileURL(&$shortCodeArgs) {
        $profileURL = isset($shortCodeArgs['profile-url']) ? $shortCodeArgs['profile-url'] : "";
        unset($shortCodeArgs['profile-url']);
        return $profileURL;
    }

    private function extractAndRemovePageSize(&$shortCodeArgs) {
        $pageSize = isset($shortCodeArgs['page-size']) ? $shortCodeArgs['page-size'] : "";
        unset($shortCodeArgs['page-size']);
        return $pageSize;
    }

    private function extractAndRemoveSavedSearch(&$shortCodeArgs) {
        $savedSearch = isset($shortCodeArgs['saved-search']) ? $shortCodeArgs['saved-search'] : "";
        unset($shortCodeArgs['saved-search']);
        return $savedSearch;
    }

    private function extractAndRemoveSearchToggle(&$shortCodeArgs)
    {
        $searchBox = in_array('search', $shortCodeArgs);
        $shortCodeArgs = array_diff($shortCodeArgs, array('search'));
        return $searchBox;
    }

    private function extractAndRemoveSites(&$shortCodeArgs)
    {
        if (!isset($shortCodeArgs['sites'])) {
            return array();
        }
        $sites = array_map('trim', explode(',', $shortCodeArgs['sites']));

        unset($shortCodeArgs['sites']);

        return $sites;
    }

    private function extractAndRemoveDropdown(&$shortCodeArgs) {
        if (!in_array('dropdown', $shortCodeArgs)) {
            return false;
        }

        $shortCodeArgs = array_diff($shortCodeArgs, array('dropdown'));

        return true;
    }

    public function getContactsFromAPI($sites, $filter, $select) {
        // $waAPIKeys = SettingsService::getWAapiKeys();

        // if (empty($waAPIKeys)) {
        //     throw new Exception("WildApricot API Keys not configured. Please visit Settings->WildApricot For WP");
        // }

        // $sites = empty($sites) ? reset($waAPIKeys) : $sites;

        // $contacts = array();
        // foreach ($sites as $site) {
        //     foreach ($waAPIKeys as $key) {
        //         if (!strcasecmp($key['site'], $site)) {
        //             $waService = new WAService($key['key']);
        //             $waService->init();
        //             $contacts = array_merge($contacts, $waService->getContactsList($filter, $select));
        //         }
        //     }

        // }

        $waService = new WAService();
        // $waService->initWithCache();
        // $waService->init();
        $contacts = $waService->getContactsList($filter, $select);

        return $contacts;
    }

    public function filterContactsWithSavedSearch($contacts, $savedSearchId) {
        // $waAPIKeys = SettingsService::getWAapiKeys();
        // if (empty($waAPIKeys)) {
        //     throw new Exception("WildApricot API Keys not configured. Please visit Settings->WildApricot For WP");
        // }
        // $outer_idx = array_key_first($waAPIKeys);

        // $key = $waAPIKeys[$outer_idx]['key'];

        // $waService = new WAService($key);

        // $waService->init();

        $waService = new WAService();
        // first need to make a request to the saved search
        $savedSearch = $waService->getSavedSearch($savedSearchId);

        $filtered_contacts = [];

        foreach ($contacts as $contact) {
            // if contact ID not in saved search thing then don't put it in array
            if (in_array($contact['Id'], $savedSearch['ContactIds'])) {
                array_push($filtered_contacts, $contact);
            }
        }

        return $filtered_contacts;
        // returns list of contact IDs.

        // from here i have two options:
        // make a request to get the entire contact list with needed fields, and filter out contacts not included in the search
        // OR
        // make a request for each contact, filter out unwanted fields, then add them all to an array


    }

    private function filterContacts($contacts, $args) {
        $contacts = new Contacts($contacts);
        $contacts->filterFieldValues($args);
        $contacts = $contacts->getFieldValuesOnly();

        return $contacts;
    }

    private function searchContactsByKeywords($contacts, $keywords) {
        $contacts = new Contacts($contacts);
        $contacts = $contacts->searchByKeywords($keywords);
        return $contacts;
    }

    private function getPictureFromAPI($url) {
        // $waAPIKeys = SettingsService::getWAapiKeys();
        // if (empty($waAPIKeys)) {
        //     throw new Exception("WildApricot API Keys not configured. Please visit Settings->WildApricot For WP");
        // }
        // $outer_idx = array_key_first($waAPIKeys);

        // $key = $waAPIKeys[$outer_idx]['key'];

        // $waService = new WAService($key);

        // $waService->init();

        $waService = new WAService();

        $picture = $waService->getPicture($url);

        return $picture;
    }
}
?>
