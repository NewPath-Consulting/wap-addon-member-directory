<?php

namespace WAWP\Memdir_Block\classes;

use WAWP\Memdir_Block\classes\ContactsUtils;

class ContactsListingPersistor {

    const PREFIX = "wa4wp-";
    private $sites;
    private $filter;
    private $select;
    private $args;

    public function __construct($sites, $profileURL, $filter, $args) {
        $this->sites = $sites;
        $this->profileURL = $profileURL;
        $this->filter = $filter;
        $this->select = ContactsUtils::generateSelectStatement($args);
        $this->args = $args;
    }

    public function save() {

        $options['args'] = $this->args;

        if (!empty($this->profileURL)) {
            $options['profile-url'] = $this->profileURL;
        }

        if (isset($this->filter)) {
            $options['filter'] = $this->filter;
        }

        if(!empty($this->sites)) {
            $options['sites'] = $this->sites;
        }

        $storageKey = $this->genListingStorageKey();

        add_option(self::PREFIX . $storageKey, $options);
        return $storageKey;
    }

    public static function getPersistedData($key) {
        return get_option(self::PREFIX . $key);
    }

    private function genListingStorageKey() {
        return MD5(implode($this->sites) . $this->filter . $this->select);
    }
}
?>
