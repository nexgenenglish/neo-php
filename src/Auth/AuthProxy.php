<?php

namespace Neo\Auth;

class AuthProxy
{
    /**
     * Flagging to retrieve which server is found from last api call
     *
     * @var string
     */
    protected $lastServerFound = "";

    /**
     * List of auth shadowing SSO servers
     *
     * @var array Auth
     */
    protected $auths = [];

    /**
     * AuthMultiServer constructor
     *
     * @param array $httpClients
     * @param array $endpoints
     * @param array $configs
     */
    public function __construct(array $httpClients, array $endpoints = [], array $configs = [])
    {
        foreach ($httpClients as $key => $httpClient) {
            $this->auths[$key] = new Auth(
                $httpClient,
                (isset($endpoints[$key])) ?: [],
                (isset($configs[$key])) ?: []
            );
        }
    }

    /**
     * Retrieve token from SSO services based on given credential.
     *
     * @param array $credential
     *
     * @return Token|null
     */
    public function token(array $credential)
    {
        foreach ($this->auths as $server => $auth) {
            if($result = $auth->token($credential)) {
                $this->lastServerFound = $server;
                return $result;
            }
        }

        return null;
    }

    /**
     * Verify given token from SSO services.
     *
     * @param Token $token
     *
     * @return bool
     */
    public function verify(Token $token)
    {
        foreach ($this->auths as $server => $auth) {
            if($result = $auth->verify($token)) {
                $this->lastServerFound = $server;
                return $result;
            }
        }

        return false;
    }

    /**
     * Retrieve user info based on given token.
     *
     * @param Token $token
     *
     * @return mixed|null
     */
    public function user(Token $token)
    {
        foreach ($this->auths as $server => $auth) {
            if($result = $auth->user($token)) {
                $this->lastServerFound = $server;
                return $result;
            }
        }

        return false;
    }

    /**
     * Login authorize given credential and returns user (with token, acl and profile).
     *
     * @param array $credential
     *
     * @return User|false
     */
    public function login(array $credential)
    {
        foreach ($this->auths as $server => $auth) {
            if($result = $auth->login($credential)) {
                $this->lastServerFound = $server;
                return $result;
            }
        }

        return false;
    }

    /**
     * Retrieve last server found
     *
     * @return string
     */
    public function getLastServerFound()
    {
        return $this->lastServerFound;
    }
}
