<?php
namespace WAWP\Memdir_Block\classes;

use \WAWP\Log as Log;
use \WAWP\Data_Encryption;
use \WAWP\WA_API;
// From:
//  s://github.com/WildApricot/ApiSamples/blob/master/PHP/sampleApplication.php
class WaApiClient
{
    const AUTH_URL = 'https://oauth.wildapricot.org/auth/token';
    const USER_AGENT = 'WildApricotPress/1.0';

    private $wa_api;
    private $token;

    public function __construct() {
        try {
            $this->initToken();
        } catch (\Exception $e) {
            Log::wap_log_error($e->getMessage(), 1);
        }
    }

    public function getAccountID() {
        $dataEncryption = new Data_Encryption();

        $account_id_e = get_transient(\WAWP\WA_Integration::ADMIN_ACCOUNT_ID_TRANSIENT);
        $account_id = $dataEncryption->decrypt($account_id_e);

        return $account_id;
    }

    public function initToken() {
        $access_data = WA_API::verify_valid_access_token();
        $this->wa_api = new WA_API($access_data['access_token'], $access_data['account_id']);
        $this->token = $access_data['access_token'];
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

        if (!$response) {
            throw new \Exception('failed making request');
        }

        $response_data = $response['body'];

        if ($isPicture) {
            return $response_data;
        }

        return json_decode($response_data, true);
    }

    public function __destruct()
    {
        $this->token = null;
    }
}
?>
