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

  public function __construct($apiKey)
  {
    $this->apiKey = $apiKey;
  }

  public function init()
  {
    $this->apiClient = new WaApiClient($this->apiKey);
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

  //can this be broader than for getting list, ie featured member?
  public function controlAccess($contacts = array(), $filter = null, $select = null) {
    //is user member or public
    $member = false;
    $currentUserStatus = get_user_meta(get_current_user_id(), 'wawp_user_status_key'); 
    if($currentUserStatus == "Active" || $currentUserStatus == "PendingRenewal") { //contains or [0] bc array?
      $member = true;
    }
    
    //get /contactfields to see/store what things are allowed to who
    $contactFields = $this->getContactFields();
    if($contactFields['statusCode'] != 200) {
      return $contactFields; //Error: if the restriction of everything can't be determined, can't give information, return the  
    }
    
    $contactFields = array_values($contactFields[0]['body']); //is the [0] needed?
    //for each field store the access level
    $defaultAccess = array();
    foreach($contactFields as $contactField) {
      $defaultAccess[$contactField['SystemCode']] = $contactField['Access']; 
      //fieldname or system code? both are passed, let's use the code though
    }

    if(empty($select)) {
      return $contacts; //can return because there is no content selected to return
    } else {
      $select = str_replace("'", '', $select);
      $select = explode(',', $select); //make into array
    }

    $filterExists = false;
    $filters = array();
    if(!empty($filter)) {
      // extract each term, put in array
    }

    foreach($contacts as $contact => $contactInfo) {
      foreach($contactInfo["FieldValues"] as $field => $value) { //test this. becasue each field values is blank, can't just lookup, have to iterate through all.. average o(n) bc hashtable o(1)
        $SystemCode = $value["SystemCode"];
        $access = null;

        //would it be faster or slower to keep track of how many filters and selects there are and then if 0 continue to next contact early?

        if($filterExists && isset($filters[$SystemCode])) { //if a filter exists on this attribute
          //find privacy
          $access = $defaultAccess[$SystemCode]; //get default privacy setting
          if(isset($value["CustomAccessLevel"])) { //if CustomAccessLevel exists
            $access = $value["CustomAccessLevel"]; //custom takes priority always
          }
          if($access == "Nobody" || ($access == "Members" && !$member)) {
            //exclude entire contact (how??) continue to next contact
          }
        } 
        if(isset($select[$SystemCode])) {
          if(!isset($access)) { //access not set
            $access = $defaultAccess[$SystemCode]; //get default privacy setting
            if(isset($value["CustomAccessLevel"])) { //if CustomAccessLevel exists
              $access = $value["CustomAccessLevel"]; //custom takes priority always
            }
          } //access is set
          if($access == "Nobody" || ($access == "Members" && !$member)) {
            //actually, this contact[attr[value]] = "" ; secret time! 
          }  
        } else {
          //exclude this attribute
          //wait, this already happenes, select has already selected I think
          //it sends some extras 
          //aaaaaa this was actually pointless, this will only be handling filtered values, will have to reformat and select again for queries
        }
      }
    }
    return $contacts;
    //id, field name, access [Nobody, Members, Public]
    //No matching records (only opted-in members are included)
    //be aware of fieldname vs system code. find good way to test. ask about swag
  }

  public function getContactsList($filter = null, $select = null)
  {
    $queryParams = array(
      '$async' => 'false'
    );

    if (!empty($filter)) {
      $queryParams = array_merge($queryParams, array('$filter' => $filter));
    }

    if (!empty($select)) {
      $queryParams = array_merge($queryParams, array('$select' => $select));
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

    return $this->controlAccess(array_values($contacts['Contacts']), $filter, $select); 
  }

  private function getAccountDetails()
  {
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