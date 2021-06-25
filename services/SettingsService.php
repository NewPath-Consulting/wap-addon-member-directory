<?php

namespace PO\services;

class SettingsService
{
    private static $_instance;

    const WA_CONNECTOR_SETTINGS_NAME = "wa-connector-settings";
    const WA_CONNECTOR_SETTINGS_API_KEYS = "wa-connector-api-keys";

    public static function getInstance()
    {
        if (!is_object(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct() {}

    public static function getWAapiKeys()
    {
        // TODO; change to spencer's api keys
        $options = get_option(self::WA_CONNECTOR_SETTINGS_NAME);

        if (empty($options) || empty($options[self::WA_CONNECTOR_SETTINGS_API_KEYS])) {
            return array();
        }
        return $options[self::WA_CONNECTOR_SETTINGS_API_KEYS];
    }
}
?>
