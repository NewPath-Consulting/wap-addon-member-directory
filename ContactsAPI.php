<?php

use WAWP\Memdir_Block\services\WAService;
use WAWP\Memdir_Block\services\SettingsService;
use WAWP\Memdir_Block\classes\ContactsUtils;
use WAWP\Memdir_Block\classes\Contacts;
use WAWP\Memdir_Block\classes\ContactsListingPersistor;

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
            wp_register_script('wawp_profile_links', plugins_url('js/profiles.js', __FILE__), array('jquery'), true);
        });

    }

    private function registerRESTRoutes() {
        add_action('rest_api_init', function () {
            register_rest_route('wafw/v1', '/contacts/search/', array(
              'methods' => 'GET',
              'callback' => array($this, 'contactsRestRoutes'),
              'permission_callback' => '__return_true'
            ));

            register_rest_route('wawp/v1', '/contacts/fields/', array(
                'methods' => 'GET',
                'callback' => array($this, 'contactFieldsRestRoute'),
                'permission_callback' => '__return_true'
            ));

            register_rest_route('wawp/v1', '/savedsearches/', array(
                'methods' => 'GET',
                'callback' => array($this, 'savedSearchesRestRoute'),
                'permission_callback' => '__return_true'
            ));

            register_rest_route('wawp/v1', '/profiles/', array(
                'methods' => 'GET',
                'args' => array(),
                'callback' => array($this, 'profileShortcodeRestRoute'),
                'permission_callback' => '__return_true'
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
        $waService = new WAService();

        $data = $waService->getContactFields();

        $response = new WP_REST_Response($data, 200);

        // Set headers.
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        return $response;
    }

    public function savedSearchesRestRoute() {
        $waService = new WAService();

        $data = $waService->getSavedSearches();

        $response = new WP_REST_Response($data, 200);

        // Set headers.
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        return $response;
    }

    public function profileShortcodeRestRoute(WP_REST_Request $request) {
        $userID = $request['id'];
        $fields = $request['fields'];
        $hideResFields = $request['hideResFields'];
        $shortcode = "[wa-profile ";
        if (!empty($fields)) {
            foreach ($fields as $field) {
                $shortcode = $shortcode . "'" . $field . "' ";
            }
        }

        $shortcode = $shortcode . 'user-id="' . $userID . '"';

        if ($hideResFields) {
            $shortcode = $shortcode . ' hide_restricted_fields';
        }

        $shortcode = $shortcode . ']';
        // $shortcode = "[wa-profile 'Photo 2' 'User ID' 'My First name' 'Middle Name' 'Last name' 'Job Title' 'Email' 'Phone' user-id='" . $userID . "']";
        $output = do_shortcode($shortcode);

        $response = new WP_REST_Response($output, 200);

        // Set headers.
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        return $response;
    }

    private function register_shortcodes() {
        add_shortcode("wa-contacts", array($this, "waContactsShortcode"));
    }

    public function waContactsShortcode($args, $content = null) {
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

        $profile = $this->extractAndRemoveProfileToggle($args);

        $pageSize = $this->extractAndRemovePageSize($args);

        $savedSearch = $this->extractAndRemoveSavedSearch($args);

        $hideResField = $this->extractAndRemoveRestrictedFieldsToggle($args);

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

        return $this->render($contacts, $cssClass, $searchBox, $profileURL, $pageSize, $profile, $queryHash, $dropdownList, $hideResField);
    }

    public function render($contacts, $cssClass, $searchBox, $profileURL, $pageSize, $profile, $queryHash, $dropdownList, $hideResField) {

        $pageSizeNum = (int)$pageSize;
        $pagination = count($contacts) > $pageSizeNum;

        ob_start();
        echo '<div class="' . esc_html($cssClass) . '">';

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

        if ($profile) {
            wp_enqueue_script('wawp_profile_links');
            wp_localize_script('wawp_profile_links', 'wawp_pagination_toggle', array('pagination' => $pagination, 'search' => $searchBox));
        }

        foreach ($contacts as $contact) {
            $this->renderContactDiv($contact, $profileURL, $profile, $hideResField);
        }

        echo '</div>';
        if ($pagination) {
            echo '<div class="wa-pagination"></div>';
        }
        echo "</div>";

        return ob_get_clean();
    }

    private function renderContactDiv($fields, $profileURL="", $profile = false, $hideResField) {
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
                echo '<img src="data:image/' . esc_attr($imgType) . ';base64,' . 
                    esc_html($picture) . '"/>';
            } else if ($field['Value'] == "🔒 Restricted" && $hideResField) {
                continue;
            } else if (is_array($field['Value'])) {
                $this->renderNestedFieldValuesList($field['FieldName'], $field['Value']);
            } else if ($field['Value'] === "") {
                continue;
            } else {
                $this->renderDivTag(
                    array(
                        'class' => sanitize_title_with_dashes($field['FieldName']),
                        'data-wa-label' => esc_html($field['FieldName'])
                    ),
                    esc_html(trim(var_export($field['Value'], true), '\''))
                );
            }
        }

        if ($profileURL) {
            $profileURL = rtrim($profileURL, "/");

            $userID = $fields['Id'];

            $url = '/' . $profileURL . '?user-id=' . $userID;
            echo '<a class="wa-more-info" href="' . esc_url($url) . '">More info</a>'; 
        }


        if ($profile) {
            $userID = $fields['Id'];
            echo '<div class="wa-profile-link" id="profile-link" data-user-id="' . esc_attr($userID) . '">View profile</div>';
        }

        echo '</div>';
    }

    private function renderFieldValuesList($fields, $profileURL="") {
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
                        'class' => sanitize_title_with_dashes($field['FieldName']) . " empty",
                        'data-wa-label' => esc_html($field['FieldName'])
                    )
                );
            } else {
                $this->renderLITag(
                    array(
                        'class' => sanitize_title_with_dashes($field['FieldName']),
                        'data-wa-label' => esc_html($field['FieldName'])
                    ),
                    esc_html(trim(var_export($field['Value'], true), '\''))
                );
            }
        }

        if ($profileURL) {
            $profileURL = rtrim($profileURL, "/");

            $userID = $fields['Id'];

            $url = '/' . $profileURL . '?user-id=' . $userID;

            echo '<a class="wa-more-info" href="' . esc_url($url) . '">More info</a>';
        }

        echo '</ul>';
    }

    private function renderNestedFieldValuesList($fieldName, $fields) {
        if (empty($fields) || !is_array($fields)) {
            return;
        }

        if (isset($fields['Id'])) {
            $liContent = isset($fields['Label']) ? esc_html($fields['Label']) : "";
        } else {
            $liContent = '<ul>';
            foreach ($fields as $field) {
                $liContent .= '<li>' . esc_html($field['Label']) . '</li>';
            }
            $liContent.= '</ul>';
        }

        $this->renderLITag(array("class" => sanitize_title_with_dashes($fieldName), "data-wa-label" => $fieldName), $liContent);

    }

    private function renderLITag($attrs, $value="") {
        $attr = array_reduce(array_keys($attrs), function($carry, $key) use ($attrs) {
            return $carry . ' ' . $key . '="' . esc_html($attrs[$key]) . '"';
        });
        echo '<li ' . esc_attr($attr) . '>' . esc_html($value) . '</li>';
    }

    private function renderDivTag($attrs, $value="") {
        $attr = array_reduce(array_keys($attrs), function($carry, $key) use ($attrs) {
            return $carry . ' ' . $key . '="' . esc_html($attrs[$key]) . '"';
        });
        echo '<div ' . esc_attr($attr) . '>' . esc_html($value) . '</div>';
    }

    private function renderSearchbox($searchId="") {
        $searchBoxName = 'wa-contacts-search';
        echo '<form class="' . esc_attr($searchBoxName) . '" data-search-id="' . esc_attr($searchId) . '">';
        echo '<input type="text" name="' . esc_attr($searchBoxName) . '">';
        echo '<input type="submit" name="submit" value="Search">';
        echo '</form>';
    }

    private function renderDropdown($sites) {

        if(empty($sites) || sizeof($sites) <= 1) {
            return;
        }

        $sites = array_merge(array("Show All"), $sites);

        $selectOptions = array_map(function($option) {
            return '<option>' . esc_html($option) . '</option>';
        }, $sites);

        $selectOptions = implode("\n", $selectOptions);

        echo '<select class="sites">';
        echo esc_html($selectOptions);
        echo "</select>";
    }

    private function cleanupContent($content) {
        $content = strip_tags($content);
        $content = trim($content);
        $content = $this->convertContentToLineArray($content);

        return $content;
    }

    private function convertContentToLineArray($content) {
        return array_map('trim', explode(PHP_EOL, $content));
    }

    private function extractAndRemoveCSSClass(&$shortCodeArgs) {
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

    private function extractAndRemoveSearchToggle(&$shortCodeArgs) {
        $searchBox = in_array('search', $shortCodeArgs);
        $shortCodeArgs = array_diff($shortCodeArgs, array('search'));
        return $searchBox;
    }

    private function extractAndRemoveProfileToggle(&$shortCodeArgs) {
        $profile = in_array('profile', $shortCodeArgs);
        $shortCodeArgs = array_diff($shortCodeArgs, array('profile'));
        return $profile;
    }

    private function extractAndRemoveRestrictedFieldsToggle(&$shortCodeArgs) {
        $resFields = in_array('hide_restricted_fields', $shortCodeArgs);
        $shortCodeArgs = array_diff($shortCodeArgs, array('hide_restricted_fields'));
        return $resFields;
    }

    private function extractAndRemoveSites(&$shortCodeArgs) {
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
        $waService = new WAService();
        $contacts = $waService->getContactsList($filter, $select);

        return $contacts;
    }

    public function filterContactsWithSavedSearch($contacts, $savedSearchId) {
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
        $waService = new WAService();

        $picture = $waService->getPicture($url);

        return $picture;
    }
}
?>
