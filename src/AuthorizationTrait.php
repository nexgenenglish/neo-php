<?php

namespace Neo;

use Neo\Auth\Token;
use Neo\Exceptions\ConfigurationException;

trait AuthorizationTrait
{
    /**
     * Authorization token.
     *
     * @var Token
     */
    public $authorizationToken;

    /**
     * Set authorization token.
     *
     * @param $token
     * @param $prefix
     *
     * @return $this
     */
    public function useAuthorizationToken($token, $prefix = "Bearer ")
    {
        $this->authorizationToken = $prefix . $token;

        return $this;
    }

    /**
     * Get authorization token.
     *
     * @return string
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     * Check whether authorization token set.
     *
     * @return bool
     */
    public function isAuthorizationTokenSet()
    {
        return ($this->authorizationToken) ? true : false;
    }

    /**
     * Check whether authorization token set or throw an exception.
     *
     * @throws ConfigurationException
     */
    public function authorizationTokenSetOrFail()
    {
        if (!$this->isAuthorizationTokenSet()) {
            throw new ConfigurationException('missing authorization token');
        }

        return $this;
    }
}
