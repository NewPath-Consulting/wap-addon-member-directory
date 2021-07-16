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
      return $contactFields; //Error: if the restriction of everything can't be determined, can't give information  
    }
    
    $contactFields = array_values($contactFields[0]['body']); //is the [0] needed?
    //for each field store the access level
    $defaultAccess = array();
    foreach($contactFields as $contactField) {
      $defaultAccess[$contactField['SystemCode']] = $contactField['Access']; 
    }

    if(empty($select)) {
      return $contacts; //can return because there is no content
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
      if($filterExists) {
        // for each filter term,
        foreach($filters as $filter)
          $access = $defaultAccess[$filter]; //get default privacy setting
          //if [custom] exist
          //access = that 
          if($access == "Nobody" || ($access == "Members" && !$member)) {
            //exclude entire contact (how??) continue to next contact
          }
      }
      //for each selected
      foreach($select as $term) {
        //eee not an easy access? maybe some better way than looping, rip. 
        //if must loop, best way is go through all, check if thing is a filtered term, that way only 1 pass
        $access = $defaultAccess[$term]; //get default privacy setting
        //if [custom] exist
          //access = that
            
        if($access == "Nobody" || ($access == "Members" && !$member)) {
          //actually, this contact[attr] = "" ; secret time! // is this going to cause type issues or smth?
        }  // otherwise it's all good
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