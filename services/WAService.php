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
  public function controlAccess($contacts = array(), ) {
    //if status != 200, return
    //take in filter/select?

    //access to this controlled by wa data
    //is user member or public
    $member = false;
    $currentUserStatus = get_user_meta(get_current_user_id(), 'wawp_user_status_key'); 
    if($currentUserStatus == "Active" || $currentUserStatus == "PendingRenewal") { //contains or [0] bc array?
      $member = true;
    }
    
    //get /contactfields to see/store what things are allowed to who
    $contactFields = $this->getContactFields();
    //for each field store the access level
      //array of key value pairs to enum maybe; all, member, no
    //store all the terms in query

    
    // for each contact
      // for each query term, 
        //check default, check custom, 
            //if any of them are private, exclude entire contact

      //for each selected
        //what is the default privacy, set that as privacy does custom exist? if so, that overrides
            //if good, aight
            //else set attribute to null or dummy. is this going to cause type issues or smth? 


    //No matching records (only opted-in members are included)
    return $contacts;

    // status code 200 ie normal
    //id, field name, access [Nobody, Members, Public]

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

    return $this->controlAccess(array_values($contacts['Contacts'])); 
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