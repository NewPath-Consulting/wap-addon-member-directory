<?php
namespace PO\classes;

use WAWP\Data_Encryption;
$path = wp_normalize_path(ABSPATH . 'wp-content/plugins/Wild-Apricot-Press/src/class-data-encryption.php');

require_once ($path);
// From:
//  s://github.com/WildApricot/ApiSamples/blob/master/PHP/sampleApplication.php
class WaApiClient
{
    const AUTH_URL = 'https://oauth.wildapricot.org/auth/token';

    private $tokenScope = 'auto';

    private static $_instance;
    private $token;

    // public function __construct($apiKey) {

    //   if (!extension_loaded('curl')) {
    //     throw new \Exception('cURL library is not loaded');
    //   }
    //   $this->initTokenByApiKey($apiKey);
    // }

    // TODO: try/catch
    public function __construct() {
        $apiKey = $this->getApiKey();
        $this->initTokenByApiKey($apiKey);
    }

    private function getApiKey() {
        $dataEncryption = new Data_Encryption();
        $credentials = get_option('wawp_wal_name');
        if (empty($credentials)) {
            throw new \Exception("WildApricot API Keys not configured.");
        }
        $e_apiKey = $credentials['wawp_wal_api_key'];
        $d_apiKey = $dataEncryption->decrypt($e_apiKey);
        return $d_apiKey;
    }

    public function initTokenByContactCredentials(
        $userName,
        $password,
        $scope = null
    ) {
        if ($scope) {
            $this->tokenScope = $scope;
        }

        $this->token = $this->getAuthTokenByAdminCredentials(
            $userName,
            $password
        );
        if (!$this->token) {
            throw new \Exception('Unable to get authorization token.');
        }
    }

    public function initTokenByApiKey($apiKey, $scope = null)
    {
        if ($scope) {
            $this->tokenScope = $scope;
        }

    public function makeRequest($url, $isPicture = false, $verb = 'GET', $data = array())
    {
        if (!$this->token) {
            throw new \Exception(
                'Access token is not initialized. Call initTokenByApiKey or initTokenByContactCredentials before performing requests.'
            );
        }

        // construct headers with authorization token
        $headers = array(
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
            'User-Agent' => self::USER_AGENT
        );

        $args = array();

        // add data to request args and headers
        if (!empty($data)) {
            $jsonData = json_encode($data);

            $args['body'] = $data;

            $headers = array_merge($headers, array(
                'Content-Length: ' . strlen($jsonData)
            ));
        }

        $args['headers'] = $headers;

        // make post request to hook and decode response data
        if ($verb == 'GET') {
            $response = wp_remote_get($url, $args);
        } else if ($verb == 'POST') {
            $response = wp_remote_post($url, $args);
        }
        $response_data = $response['body'];
        if ($isPicture && $response_data) {
            return $response_data;
        }

        if (!$response_data) {
            throw new \Exception('failed making request');
        }

        $result = json_decode($response, true);
        curl_close($ch);
        return $result['access_token'];
    }

    // public static function getInstance()
    // {
    //     if (!is_object(self::$_instance)) {
    //         self::$_instance = new self();
    //     }

    //     return self::$_instance;
    // }

    // final public function __clone()
    // {
    //     throw new Exception(
    //         'It\'s impossible to clone singleton "' . __CLASS__ . '"!'
    //     );
    // }

    public function __destruct()
    {
        $this->token = null;
    }
}
?>
