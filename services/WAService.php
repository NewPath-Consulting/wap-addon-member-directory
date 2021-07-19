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
    //is current wp user member or public
    $member = false;
    $currentUserStatus = get_user_meta(get_current_user_id(), 'wawp_user_status_key'); 
    if($currentUserStatus == "Active" || $currentUserStatus == "PendingRenewal") { //contains or [0] bc array?
      $member = true;
    }
    
    if(empty($select)) {
      return $contacts; //can return because there is no content
    } 

    //get /contactfields to see/store what things are allowed for what levels
    $contactFields = $this->getContactFields(); //this does every field
    if($contactFields['statusCode'] != 200) {
      return $contactFields; //Error: if the restriction of everything can't be determined, can't give information, return the error 
    }

    $contactFields = array_values($contactFields[0]['body']); //is the [0] needed?
    //for each field store the access level
    //could possibly store less? is it better to store everything or filter what is stored
    //caching this would be good, how? //TODO nice to have
    $defaultAccess = array();
    foreach($contactFields as $contactField) {
      $defaultAccess[$contactField['FieldName']] = $contactField['Access'];
    }

    //extra information gets passed, should that be removed here? go back to some of previous version

    $exclude = false;
    $filters = array();
    $excludedContacts = array();

    if(!empty($filter)) { //if filter exists
      // extract each term, put in array $filters
      //TODO

      //select filters with api call, privacy turned off
      $filterData = $this -> getContactsList(null, $filters, false);
      if(empty($filterData)) {
        return array(); //can't return if can't guarentee privacy
      }
      //loop each contact and check privacy for each filter (slow, limit filters)
      foreach($filterData as $contact => $contactInfo) {
        foreach($contactInfo["FieldValues"] as $field => $value) { //for each filtered attribute for each contact
          $FieldName = $value["FieldName"];
          $access = $defaultAccess[$FieldName]; //get default privacy setting
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
    }

    foreach($contacts as $contact => $contactInfo) {
      if($exclude && isset($excludedContacts[$contactInfo["Id"]])) { //an attribute this contact has private is being filtered on, so can't show contact
        continue; 
      }
      foreach($contactInfo["FieldValues"] as $field => $value) { //for each selected value, which have already been selected by the api
        $FieldName = $value["FieldName"]; //combine below once tested
        $access = $defaultAccess[$FieldName]; //get default privacy setting
        if(isset($value["CustomAccessLevel"])) { //if CustomAccessLevel exists
          $access = $value["CustomAccessLevel"]; //custom takes priority always
        }
        if(!($access == "Public" || ($access == "Members" && $member))) { //if not either of allowed (this way an error defaults private)
          //secret time! hide this specific value
          $value["Value"] = null; //type issues?
        }  
      }
    }
    return $contacts;
    //id, field name, access [Nobody, Members, Public]
    //field name, not system code because that is what filter uses ???? choices
    //"No matching records (only opted-in members are included)"
    //cache
    //find good way to test
    //double check isset vs empty and things being null
  }

  public function getContactsList($filter = null, $select = null, $private = true)
  {
    $queryParams = array(
      '$async' => 'false'
    );

    if (!empty($filter)) {
      $queryParams = array_merge($queryParams, array('$filter' => $filter));
    }
    
    //only Active or PendingRenewal members show up in member directory (or featured member). If this is used elsewhere, should be run with pr=ivate = false
    //TODO check for other uses
    if($private) {
      if (!empty($select)) {
        $queryParams = array_merge($queryParams, array('$select' => ($select . "AND (Status eq 'Active' OR Status eq 'PendingRenewal')" )));
      } else {
        $queryParams = array_merge($queryParams, array('$select' => "(Status eq 'Active' OR Status eq 'PendingRenewal')"));
      }
    } else {
      if (!empty($select)) {
        $queryParams = array_merge($queryParams, array('$select' => ($select)));
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