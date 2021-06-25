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

    return array_values($contacts['Contacts']);
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