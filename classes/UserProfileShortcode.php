<?php

namespace PO\classes;
use PO\classes\ContactsUtils;
use PO\services\WAService;
use PO\services\SettingsService;

class UserProfileShortcode {
    private static $SHORTCODE_NAME = 'wa-profile';

    public function __construct() {

        $this->registerShortcode();
    }

    private function registerShortcode() {
        add_shortcode(self::$SHORTCODE_NAME, array($this, "waUserProfileShortcode"));
    }

    public function waUserProfileShortcode($args, $content = null)
    {
        if(empty($_REQUEST['user-id'])) {
            return;
        }

        $userID = sanitize_key($_REQUEST['user-id']);

        $filterStatement = array("ID eq ${userID}"); //ID by system code not field name for content restriction

        $filter = ContactsUtils::generateFilterStatement($filterStatement);
        $select = ContactsUtils::generateSelectStatement($args);

        $sites = array_map('trim', explode(',', $args['sites']));
        unset($args['sites']);

        $waAPIKeys = SettingsService::getWAapiKeys();

        if (empty($waAPIKeys)) {
            throw new Exception("WildApricot API Keys not configured. Please visit Settings->WildApricot For WP");
        }

        $sites = empty($sites) ? reset($waAPIKeys) : $sites;

        $contacts = array();
        foreach ($sites as $site) {
            foreach ($waAPIKeys as $key) {
                if (!strcasecmp($key['site'], $site)) {
                    $waService = new WAService($key['key']);
                    $waService->init();
                    $contacts = array_merge($contacts, $waService->getContactsList($filter, $select, true)); // content restriction 
                }
            }
        }

        $contacts = new Contacts($contacts);
        $contacts->filterFieldValues($args); //is this not redundant

        $contacts = $contacts->getFieldValuesOnly();

        return $this->render($contacts[0]);
    }

    private function render($userProfile=NULL, $class="wa-profile") {
        ob_start();

        if (empty($userProfile)) {
            echo "No matching records (only opted-in members are included)";
            return ob_get_clean();
        }

        echo "<div class=\"${class}\">";
        foreach ($userProfile as $userFields) {

            $userFieldName = sanitize_title_with_dashes($userFields['FieldName']);
            $userFieldValue = $userFields['Value'];
            $userFieldNameLabel = htmlspecialchars($userFields['FieldName']);

            if (is_array($userFieldValue)) {
                $userFieldValue = $userFieldValue['Label'];
            }

            if (empty($userFieldName) || empty($userFieldValue)) {
                continue;
            }

            echo "<div class=\"${userFieldName}\" data-wa-label=\"${userFieldNameLabel}\">";
            echo htmlspecialchars($userFieldValue);
            echo "</div>";
        }
        echo "</div>";

        return ob_get_clean();
    }

}

?>
