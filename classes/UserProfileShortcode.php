<?php

namespace WAWP\Memdir_Block\classes;
use WAWP\Memdir_Block\classes\ContactsUtils;
use WAWP\Memdir_Block\services\WAService;
use WAWP\Memdir_Block\services\SettingsService;
use Walker;

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
        // get user id from shortcode arguments
        $userID_arg = $this->extractAndRemoveUserID($args);
        if(empty($_REQUEST['user-id']) && !$userID_arg) {
            return;
        }

        $userID = '';
        if (empty($_REQUEST['user-id'])) {
            $userID = $userID_arg;
        } else {
            $userID = sanitize_key($_REQUEST['user-id']);
        }

        $filterStatement = array("ID eq ${userID}"); //ID by system code not field name for content restriction

        $hideResField = $this->extractAndRemoveRestrictedFieldsToggle($args);

        $filter = ContactsUtils::generateFilterStatement($filterStatement);
        $select = ContactsUtils::generateSelectStatement($args);

        $sites = array_map('trim', explode(',', $args['sites']));
        unset($args['sites']);

        $sites = empty($sites) ? reset($waAPIKeys) : $sites;

        $waService = new WAService();
        $current_user_id = get_user_meta(get_current_user_id(), "wawp_wa_user_id");
        // $contacts;
        if($userID == $current_user_id) { //If user being displayed is the current user
            $contacts = $waService->getContactsList($filter, $select, false); //run without restriction
        } else {
            $contacts = $waService->getContactsList($filter, $select, true);
        }

        $contacts = new Contacts($contacts);

        $contacts->filterFieldValues($args);

        $contacts = $contacts->getFieldValuesOnly();


        return $this->render($contacts[0], $hideResField);
    }

    private function render($userProfile, $hideResField, $class="wa-profile")  {
        ob_start();

        if (empty($userProfile)) {
            echo 'No matching records (only opted-in members are included)';
            return ob_get_clean();
        }

        echo '<div class="' . esc_attr($class) . '">';
        foreach ($userProfile as $userFields) {
            $userFieldName = sanitize_title_with_dashes($userFields['FieldName']);
            $userFieldValue = $userFields['Value'];
            $userFieldNameLabel = esc_html($userFields['FieldName']);

            if (empty($userFieldName) || empty($userFieldValue)) {
                continue;
            }

            if ($userFieldValue == '🔒 Restricted' && $hideResField) {
                continue;
            }
            echo '<div id="' . esc_attr($userFieldName) . '" class="field">';

            echo '<span class="field-name">';
            echo esc_html($userFieldNameLabel);
            echo '</span>';

            if (ContactsUtils::isPicture($userFieldValue)) {
                // need to get picture from API
                $waService = new WAService();
                $picture = $waService->getPicture($userFieldValue['Url']);
                $imgType = ContactsUtils::getPictureType($userFieldValue['Id']);
                echo '<img src="data:image/' . esc_attr($imgType) . ';base64,' . 
                esc_html($picture) . '"/>';
            }else {
                if (is_array($userFieldValue)) {
                    $userFieldValue = $userFieldValue['Label'];
                }
                echo '<span class="' . esc_attr($userFieldName) . ' field-value" data-wa-label="' . esc_attr($userFieldNameLabel) . '">';
                echo esc_html($userFieldValue);
                echo '</span>';
            }

            echo '</div>';

        }
        echo '</div>';

        return ob_get_clean();
    }

    private function extractAndRemoveUserID(&$shortCodeArgs) {
        $userID = isset($shortCodeArgs['user-id']) ? $shortCodeArgs['user-id'] : "";
        unset($shortCodeArgs['user-id']);
        return $userID;
    }

    private function extractAndRemoveRestrictedFieldsToggle(&$shortCodeArgs) {
        $resFields = in_array('hide_restricted_fields', $shortCodeArgs);
        $shortCodeArgs = array_diff($shortCodeArgs, array('hide_restricted_fields'));
        return $resFields;
    }

}

?>
