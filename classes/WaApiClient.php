<?php
namespace PO\classes;

// From:
//  s://github.com/WildApricot/ApiSamples/blob/master/PHP/sampleApplication.php
class WaApiClient
{
    const AUTH_URL = 'https://oauth.wildapricot.org/auth/token';

    private $tokenScope = 'auto';

    private static $_instance;
    private $token;

    public function __construct($apiKey) {

      if (!extension_loaded('curl')) {
        throw new \Exception('cURL library is not loaded');
      }
      $this->initTokenByApiKey($apiKey);
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

        $this->token = $this->getAuthTokenByApiKey($apiKey);
        if (!$this->token) {
            throw new \Exception('Unable to get authorization token.');
        }
    }

    public function makeRequest($url, $isPicture = false, $verb = 'GET', $data = null)
    {
        if (!$this->token) {
            throw new \Exception(
                'Access token is not initialized. Call initTokenByApiKey or initTokenByContactCredentials before performing requests.'
            );
        }

        $ch = curl_init();
        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        );
        curl_setopt($ch, CURLOPT_URL, $url);

        if ($data) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            $headers = array_merge($headers, array(
                'Content-Length: ' . strlen($jsonData)
            ));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $jsonResult = curl_exec($ch);
        if ($isPicture && $jsonResult) {
            do_action('qm/debug', $jsonResult);
            return $jsonResult;
        }
        if ($jsonResult === false) {
            throw new \Exception(curl_errno($ch) . ': ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($jsonResult, true);
    }

    private function getAuthTokenByAdminCredentials($login, $password)
    {
        if ($login == '') {
            throw new \Exception('login is empty');
        }

        $data = sprintf(
            "grant_type=%s&username=%s&password=%s&scope=%s",
            'password',
            urlencode($login),
            urlencode($password),
            urlencode($this->tokenScope)
        );

        throw new Exception(
            'Change clientId and clientSecret to values specific for your authorized application. For details see:  https://help.wildapricot.com/display/DOC/Authorizing+external+applications'
        );
        // $clientId = 'SamplePhpApplication';
        // $clientSecret = 'open_wa_api_client';
        $authorizationHeader =
            "Authorization: Basic " .
            base64_encode($clientId . ":" . $clientSecret);

        return $this->getAuthToken($data, $authorizationHeader);
    }

    private function getAuthTokenByApiKey($apiKey)
    {
        $data = sprintf(
            "grant_type=%s&scope=%s",
            'client_credentials',
            $this->tokenScope
        );

        $authorizationHeader =
            "Authorization: Basic " . base64_encode("APIKEY:" . $apiKey);
        return $this->getAuthToken($data, $authorizationHeader);
    }

    private function getAuthToken($data, $authorizationHeader)
    {
        $ch = curl_init();
        $headers = array(
            $authorizationHeader,
            'Content-Length: ' . strlen($data)
        );
        $headers = array(
            $authorizationHeader
        );
        curl_setopt($ch, CURLOPT_URL, WaApiClient::AUTH_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception(curl_errno($ch) . ': ' . curl_error($ch));
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
