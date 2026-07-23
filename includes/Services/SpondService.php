<?php

if (!defined('ABSPATH')) {
    exit;
}

class MCC_SpondService
{
    private const API_URL = 'https://api.spond.com/core/v1/';

    private string $email;
    private string $password;

    /**
     * The current bearer token after login.
     */
    private ?string $token = null;

    public function __construct()
    {
        $this->email = get_option('mcc_spond_email', '');
        $this->password = get_option('mcc_spond_password', '');
    }

    /**
     * Returns the HTTP headers required for authenticated requests.
     */
    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => 'application/json',
        ];
    }

    public function login()
    {
        $response = wp_remote_post(
            self::API_URL . 'auth2/login',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => wp_json_encode([
                    'email' => $this->email,
                    'password' => $this->password,
                ]),
                'timeout' => 30,
            ]
        );

        if (is_wp_error($response)) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($status !== 200) {
            return new WP_Error(
                'spond_login_failed',
                $body['message'] ?? 'Unable to login to Spond.'
            );
        }

        if (empty($body['accessToken']['token'])) {
            return new WP_Error(
                'spond_login_failed',
                'No access token was returned.'
            );
        }

        $this->token = $body['accessToken']['token'];

        return true;
    }
}