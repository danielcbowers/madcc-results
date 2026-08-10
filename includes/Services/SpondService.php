<?php

if (!defined('ABSPATH')) {
    exit;
}

class SpondService
{
    private const API_URL = 'https://api.spond.com/core/v1/';
    private const GROUP_ID = '8DC8EA1B63D44122A05CF5605BF80B27';
    private const GROUP_NAME = 'Maldon and District CC';

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
        error_log('Spond login() called');
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

    /**
     * Get all events for the Maldon and District CC Spond group.
     *
     * @return array|WP_Error
     */
    public function getEvents()
    {
        if (!$this->token) {

            $login = $this->login();

            if (is_wp_error($login)) {
                return $login;
            }
        }

        $url = add_query_arg(
            [
                'groupId'  => self::GROUP_ID,
                'max'      => 100,
                'scheduled'=> 'false'
            ],
            self::API_URL . 'sponds/'
        );

        $response = wp_remote_get(
            $url,
            [
                'headers' => $this->headers(),
                'timeout' => 30,
            ]
        );

        if (is_wp_error($response)) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code($response);

        if ($status !== 200) {

            return new WP_Error(
                'spond_events_failed',
                sprintf(
                    'HTTP %d: %s',
                    $status,
                    wp_remote_retrieve_body($response)
                )
            );
        }

        return json_decode(
            wp_remote_retrieve_body($response),
            true
        );
    }

    public function getGroupId(): string
    {
        return self::GROUP_ID;
    }

    public function getAcceptedMembers(array $event): array
    {
        $accepted = $event['responses']['acceptedIds'] ?? [];

        $members = [];

        foreach ($event['recipients']['group']['members'] as $member) {

            if (in_array($member['id'], $accepted, true)) {
                $members[] = $member;
            }

        }

        return $members;
    }

    public function getGroupMembers()
    {
        if (!$this->token) {

            $login = $this->login();

            if (is_wp_error($login)) {
                return $login;
            }
        }

        $response = wp_remote_get(
            self::API_URL . 'groups/',
            [
                'headers' => $this->headers(),
                'timeout' => 30,
            ]
        );

        if (is_wp_error($response)) {
            return $response;
        }

        return json_decode(
            wp_remote_retrieve_body($response),
            true
        );
    }

    public function testConnection()
    {
        $result = $this->getGroupMembers();

        if (is_wp_error($result)) {
            return [
                'connected' => false,
                'message'   => $result->get_error_message()
            ];
        }

        return [
            'connected' => true,
            'message'   => 'Connected'
        ];
    }
}