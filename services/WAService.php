<?php
namespace WAWP\Memdir_Block\services;

require_once(plugin_dir_path(__DIR__) . "/classes/WaApiClient.php");
require_once('CacheService.php');

use \WAWP\Log as Log;
use WAWP\Memdir_Block\classes\WaApiClient;
use WAWP\Memdir_Block\services\CacheService;
//use PO\classes\ContactsUtils; //include for filter

const ACCOUNTS_API_URL = 'https://api.wildapricot.org/v2.2/accounts/';

class WAService {
    private $apiClient;
    private $accountURL = null;

    public function __construct() {
        $this->apiClient = new WaApiClient();
    }

    public function getContactFields() {
        $queryParams = array(
            'showSectionDividers' => 'false'
        );

        $query = http_build_query($queryParams);

        $url = $this->getEndpointURL() . '/contactfields?' . $query;

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
            if(!empty(array_intersect($currentUserStatus, $restricted_statuses))) { 
            $member = true;
            }
        } else {
            $member = true; //if no resticted levels, let all logged in WA users see. ASK: is this desired behavior? 
        }

        if(empty($select)) {
            return $contacts; //can return because there is no content
        } 


        $contactFields = array(); 
        try {
            $contactFields = array_values($this->getContactFields()); //get /contactfields to see/store what things are allowed for what levels
        } catch (\Exception $e) {
            Log::wap_log_error($e->getMessage(), true);
        }
        
        //How to check for error? This will cause an error if empty
        /*if($contactFields['statusCode'] != 200) { 
            return $contactFields; //Error: if the restriction of everything can't be determined, can't give information, return the error 
        }*/

        //FUTURE: caching this would be good, how? 
        $defaultAccess = array();
        foreach($contactFields as $contactField) { //for each field store the access level
            $defaultAccess[$contactField['SystemCode']] = $contactField['Access']; //could store only those in $select if no cache
            //if ever only ones in select, select will have to be by SystemCode only. This will cause issues with Saved searches as it (at least now) uses FieldName            
        }

        //filter isn't going to be used in the September 2021 version, leaving this for future developer
        //highly recommend resticting people to system code only for input
        /*$exclude = false;
        $filters = array();
        $excludedContacts = array();

        if(!empty($filters)) { //if filter exists
            // extract each term, put in array $filters 
           $filters = ContactsUtils::generateFilterStatement($filter);
                    
            //select filters with api call, privacy turned off to check privacy
            $filterData = $this -> getContactsList(null, $filters, false);
            if(empty($filterData)) {
            return array(); //can't return if can't guarentee privacy
            }
            //loop each contact and check privacy for each filter (slow, limit filters)
            //filter can only be SystemCode names for this to work, although the call will work with both            
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
    }

    public function getContactsList($filter = null, $select = null, $block = true, $private = true) {
        $queryParams = array(
            '$async' => 'false'
        );

        if ($private) { //The global restriction of a contact is a FieldValue (terrible design), so need to get 
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

        if ($private) { //FUTURE: let shown statuses be customizable
            if (!empty($filter)) {
                $queryParams = array_merge($queryParams, array('$filter' => ($filter . " AND (Status eq 'Active' OR Status eq 'PendingRenewal')" )));
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
            $this->getEndpointURL() .
            '/Contacts?' .
            $query;


        // look for contacts in cache
        $apiCache = CacheService::getInstance();
        $contacts = $apiCache->getValue($url);

        // if contacts not in cache, retrieve from api
        if (empty($contacts)) {
            if ($block) {
                $contacts = $this->getContactsBlock($query);
            } else {
                $contacts = $this->apiClient->makeRequest($url);
            }

            // save contacts in cache
            $apiCache->saveValue($url, $contacts);
        }

        // return empty array if contacts array doesn't exist in the response
        if (!array_key_exists('Contacts', $contacts)) {
            return array();
        }

        // return control access values if private is true
        if ($private) {
            return $this->controlAccess(
                array_values($contacts['Contacts']), 
                $filter, 
                $select
            ); 
        } 

        return array_values($contacts['Contacts']);
    }

    public function getContactsBlock($query) {
        $count = $this->apiClient->getContactsCount();

        $skip = 0;
        $top = 500;

        $done = false;

        $all_contacts = array(
            'Contacts' => array()
        );

        // retrieve in blocks of 500
		while (!$done) {
			// if there are more than 500 entires left, include top query
			if (($count - $skip) <= $top) {
				$top = 0;
				$done = true;
			}

			// make API request and add block to list of all contacts
			$contacts_block = $this->apiClient->getContactBlock($query, $skip, $top);
			$all_contacts['Contacts'] = array_merge(
				$all_contacts['Contacts'],
				$contacts_block['Contacts']
			);

			// increment by block size
			$skip += $top;
		}
        
        return $all_contacts;
    }

    public function getSavedSearches() {
        $url = $this->getEndpointURL() . '/savedsearches';

        $savedSearches = $this->apiClient->makeRequest($url);

        return $savedSearches;
    }

    public function getSavedSearch($savedSearchId) {
        $queryParams = array(
            'excludeArchived' => 'false'
        );
        $query = http_build_query($queryParams);
        $url = $this->getEndpointURL() . '/savedsearches/' . $savedSearchId . '?' . $query;
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
        return $picture;
    }

    private function getEndpointURL() {
        
        if (is_null($this->accountURL)) {

            try {
                $account_id = $this->apiClient->getAccountID();

            } catch (\Exception $e) {
                Log::wap_log_error($e->getMessage(), true);
            }

            $this->accountURL = ACCOUNTS_API_URL . $account_id;
        }

        
        return $this->accountURL;
    }

}