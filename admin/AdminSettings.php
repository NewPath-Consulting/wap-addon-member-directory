<?php

namespace PO\admin;
use PO\services\SettingsService;

class AdminSettings
{
    const PLUGIN_NAME = "WildApricot for WP";

    public function __construct()
    {
        $this->parentSlug = "options-general.php";
        $this->pluginSlug = "waconnector_options";

        add_action("admin_menu", array($this, "addSettingsPage"));
        add_action("admin_init", array($this, "addSettingsToPage"));
    }

    public function addSettingsPage()
    {
        add_submenu_page(
            $this->parentSlug,
            self::PLUGIN_NAME . " Settings",
            self::PLUGIN_NAME,
            "administrator",
            $this->pluginSlug,
            function () {
                echo '<div class="wrap">';
                echo '<form action="options.php" method="post">';
                settings_fields("wa-connector-settings");
                do_settings_sections($this->pluginSlug);
                submit_button();
                echo " </form>";
                echo '</div>';
            }
        );
    }

    function addSettingsToPage()
    {
        $sectionName = "WildApricot for WordPress Settings";
        add_settings_section(
            $sectionName,
            $sectionName,
            function () {
                echo "Thank you for being part of the test team for the beta version of the WildApricot for WordPress plugin";
                echo "<p>Please send all bug reports and feature requests to <a href=\"http://wpto.newpath.consulting\">http://wpto.newpath.consulting</a> or <a href=\"mailto:philoxrud@gmail.com\"/>philoxrud@gmail.com</a></p>";
            },
            $this->pluginSlug
        );

        $apiKeysOptionName = SettingsService::WA_CONNECTOR_SETTINGS_API_KEYS;
        add_settings_field(
            $apiKeysOptionName,
            'WildApricot API Keys Settings',
            function() use ($apiKeysOptionName) {
                $options = get_option(SettingsService::WA_CONNECTOR_SETTINGS_NAME);
                $timeStamp = floor(microtime(true) * 1000);
                $inputName = SettingsService::WA_CONNECTOR_SETTINGS_NAME . "[" . $apiKeysOptionName . "]";

                $siteAPIs = $options[$apiKeysOptionName];

                if (isset($siteAPIs) && !empty($siteAPIs)) {
                    foreach ($siteAPIs as $key => $value) {

                        ?>
                        <label for="">Site Name:
                            <input type="text" name="<?php echo $inputName . "[" . $key . "]" . "[site]"; ?>" value="<?php echo $value['site'];?>">
                        </label>

                        <label for="">API Key:
                            <input size=36 type="text" name="<?php echo $inputName . "[" . $key . "]" . "[key]";?>" value="<?php echo $value['key'];?>"/>
                        </label>
                        <br /><br />
                        <?php
                    }
                }

                ?>
                <label for="">Site Name:
                    <input type="text" name="<?php echo $inputName . "[" . $timeStamp . "]" . "[site]"; ?>" value="">
                </label>

                <label for="">API Key:
                    <input size=36 type="text" name="<?php echo $inputName . "[" . $timeStamp . "]" . "[key]";?>"/>
                </label>
                <?php


            ?>

            <?php
            },
            $this->pluginSlug,
            $sectionName
        );

        register_setting(
            SettingsService::WA_CONNECTOR_SETTINGS_NAME,
            SettingsService::WA_CONNECTOR_SETTINGS_NAME,
            array(
                'type' => 'string',
                'description' => 'WildApricot API Keys',
                'sanitize_callback' => function($input) {
                    $sitesAndKeys = &$input[SettingsService::WA_CONNECTOR_SETTINGS_API_KEYS];
                    foreach ($sitesAndKeys as $entry => $siteAndKey) {
                        if (empty($siteAndKey['site']) || empty($siteAndKey['key'])) {
                            unset($sitesAndKeys[$entry]);
                        }
                    }
                    return $input;
                }
            )
        );
    }
}
?>
