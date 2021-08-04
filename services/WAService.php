<?php
namespace PO\services;

require_once(plugin_dir_path(__DIR__) . "/classes/WaApiClient.php");
require_once('CacheService.php');

use PO\classes\WaApiClient;
use PO\services\CacheService;

const ACCOUNTS_API_URL = 'https://api.wildapricot.org/v2.2/accounts/';

class WAService
{
    private $apiKey;
    private $apiClient;
    private $accountURL = false;

    // public function __construct($apiKey)
    // {
    //   $this->apiKey = $apiKey;
    // }

    // public function init()
    // {
    //   $this->apiClient = new WaApiClient($this->apiKey);
    // }

    public function __construct() {
        $this->apiClient = new WaApiClient();
    }

    public function initWithCache()
    {
        $this->useCache = true;
        $this->init();
    }

    public function getContactFields() {
        $queryParams = array(
            'showSectionDividers' => 'false'
        );

        $query = http_build_query($queryParams);

        $url = $this->getAccountURL() . '/contactfields?' . $query;

        $contactFields = $this->apiClient->makeRequest($url);

        return $contactFields;
    }

    public function controlAccess($contacts = array(), $filter = null, $select = null) {
        //is current wp user member or public
        $member = false;
        $restricted_statuses = get_option('wawp_restriction_status_name');
        // If there are restricted statuses, then we must check them against the user's status
        if (!empty($restricted_statuses)) {
            $currentUserStatus = get_user_meta(get_current_user_id(), 'wawp_user_status_key'); 
            // If user's status is not in the restricted statuses, then the user cannot see the post
            if (in_array($currentUserStatus, $restricted_statuses)) {
            $member = true;
            }
        } else {
            $member = true; //if no resticted levels, let all logged in WA users see. ASK: is this desired behavior? 
        }

        if(empty($select)) {
            return $contacts; //can return because there is no content
        } 


        $contactFields = array(); 
        $contactFields = array_values($this->getContactFields()); //get /contactfields to see/store what things are allowed for what levels
        //How to check for error? This will cause an error if empty
        /*if($contactFields['statusCode'] != 200) { 
            return $contactFields; //Error: if the restriction of everything can't be determined, can't give information, return the error 
        }*/

        //FUTURE: caching this would be good, how? 
        $defaultAccess = array();
        foreach($contactFields as $contactField) { //for each field store the access level
            $defaultAccess[$contactField['SystemCode']] = $contactField['Access']; //could store only those in $select if no cache
        }

        //filter isn't going to be used in the September 2021 version, leaving this for future developer
        //highly recommend resticting people to system code only for input
        /*$exclude = false;
        $filters = array();
        $excludedContacts = array();

        if(!empty($filter)) { //if filter exists
            // extract each term, put in array $filters 
           //Actually use generateFilterStatement($filterList) from ContactsUtils class, this function already exists, nice
                    
            //select filters with api call, privacy turned off to check privacy
            $filterData = $this -> getContactsList(null, $filters, false);
            if(empty($filterData)) {
            return array(); //can't return if can't guarentee privacy
            }
            //loop each contact and check privacy for each filter (slow, limit filters)
            foreach($filterData as $contact => $contactInfo) {
            foreach($contactInfo["FieldValues"] as $field => $value) { //for each filtered attribute for each contact
                $SystemCode = $value["SystemCode"];
                $access = $defaultAccess[$SystemCode]; //get default privacy setting
                if(isset($value["CustomAccessLevel"])) { //if CustomAccessLevel exists
                $access = $value["CustomAccessLevel"]; //custom takes priority always
                }
                if(!($access == "Public" || ($access == "Members" && $member))) { //if not either of allowed (this way an error defaults private)
                $excludedContacts[] = $contactInfo["Id"]; //add this
                continue; //ie continue to next contact
                }
            } 
            }
        }
        if(!empty($excludedContacts)) {
            $exclude = true;
        }*/
                
        foreach($contacts as $contact => $contactInfo) {
            /*if($exclude && isset($excludedContacts[$contactInfo["Id"]])) { 
            continue; //an attribute this contact has private is being filtered on, so can't display contact
            }*/

            foreach($contactInfo["FieldValues"] as $field => $value) { //for each selected value
            $SystemCode = $value["SystemCode"]; 
            if(!($SystemCode == "AccessToProfileByOthers")) {    
                $access = $defaultAccess[$SystemCode]; //get default privacy setting
                if(isset($value["CustomAccessLevel"])) { //if CustomAccessLevel exists
                $access = $value["CustomAccessLevel"]; //custom takes priority always
                }
                if(!($access == "Public" || ($access == "Members" && $member))) { //if not either of allowed (this way errors default private)       
                $contacts[$contact]["FieldValues"][$field]["Value"] = "🔒 Restricted"; //hide this specific value
                } 
            } else { //SystemCode == "AccessToProfileByOthers"
                if($value["Value"] == false) { //if can be shown to others
                unset($contacts[$contact]); //exclude this contact
                } else {
                unset($contacts[$contact]["FieldValues"][$field]); //exclude access to profile by others because we included it 
                //FUTURE: let it stay if chosen? value could only be true if someone can see contact, kind of pointless
                }              
            }
            }  
            }
            return $contacts;
        //"No matching records (only opted-in members are included)" in table
    }

    public function getContactsList($filter = null, $select = null, $private = false) {
        $queryParams = array(
            '$async' => 'false'
        );

        if($private) { //The global restriction of a contact is a FieldValue (terrible design), so need to get 
            if (!empty($select)) {
            $queryParams = array_merge($queryParams, array('$select' => ($select . ",'AccessToProfileByOthers'")));
            } else {
            $queryParams = array_merge($queryParams, array('$select' => "'AccessToProfileByOthers'"));
            }
        } else {
            if (!empty($select)) {
            $queryParams = array_merge($queryParams, array('$select' => $select));
            }
        }

        if($private) { //FUTURE: let shown statuses be customizable
            if (!empty($filter)) {
            $queryParams = array_merge($queryParams, array('$filter' => ($filter . "AND (Status eq 'Active' OR Status eq 'PendingRenewal')" )));
            } else {
            $queryParams = array_merge($queryParams, array('$filter' => "(Status eq 'Active' OR Status eq 'PendingRenewal')"));
            }
        } else {
            if (!empty($filter)) {
            $queryParams = array_merge($queryParams, array('$filter' => ($filter)));
            }
        }

        $query = http_build_query($queryParams, null, '&', PHP_QUERY_RFC3986);

        $url =
            $this->getAccountURL() .
            '/Contacts?' .
            $query;

        if (isset($this->useCache)) {
            $apiCache = CacheService::getInstance();
            $contacts = $apiCache->getValue($url);

            if (empty($contacts)) {
            $contacts = $this->apiClient->makeRequest($url);
            $apiCache->saveValue($url, $contacts);
            }
        } else {
            $contacts = $this->apiClient->makeRequest($url);
        }

        if (!isset($contacts['Contacts'])) {
            return array();
        }
        if($private) {
            return $this->controlAccess(array_values($contacts['Contacts']), $filter, $select); 
        } return array_values($contacts['Contacts']);
    }  

    public function getSavedSearches() {
        $url = $this->getAccountURL() . '/savedsearches';

        $savedSearches = $this->apiClient->makeRequest($url);

        return $savedSearches;
        }

    public function getSavedSearch($savedSearchId) {
        $queryParams = array(
            'excludeArchived' => 'false'
        );
        $query = http_build_query($queryParams);
        $url = $this->getAccountURL() . '/savedsearches/' . $savedSearchId . '?' . $query;
        do_action('qm/debug', $url);
        $savedSearch = $this->apiClient->makeRequest($url);
        return $savedSearch;
    } 

    public function getPicture($pictureUrl) {
        $queryParams = array(
            'fullSize' => 'false',
            'asBase64' => 'true'
        );

        $query = http_build_query($queryParams);
        $url = $pictureUrl . '?' . $query;
        $picture = $this->apiClient->makeRequest($url, true);
        do_action('qm/debug', $picture);
        return $picture;
    }

    private function getAccountDetails() {
        $accounts = $this->apiClient->makeRequest(ACCOUNTS_API_URL);

        // TODO: handle multiple accounts, right now returns one
        return $accounts[0];
        }

        private function getAccountURL()
        {
        if (empty($this->accountURL)) {
            $account = $this->getAccountDetails();
            $this->accountURL = $account['Url'];
        }
        return $this->accountURL;
    }

}
?>
