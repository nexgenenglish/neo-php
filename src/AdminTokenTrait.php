<?php

namespace Neo;

use Neo\Auth\Auth;
use Neo\Auth\Token;
use Neo\Exceptions\ConfigurationException;

trait AdminTokenTrait
{
    /**
     * Admin token
     *
     * @var Token
     */
    public $adminToken;

    /**
     * Set admin token
     *
     * @param Token $token
     * @return $this
     */
    public function useAdminToken(Token $token)
    {
        $this->adminToken = $token;

        return $this;
    }

    /**
     * Set admin credential
     *
     * @param array $credential
     * @return $this
     * @throws Exceptions\ConfigurationException
     * @throws Exceptions\ValidationException
     */
    public function useAdminCredential(array $credential)
    {
        $auth = new Auth($this->httpClient);

        $this->adminToken = $auth->token($credential);

        return $this;
    }

    /**
     * Check whether admin token set
     *
     * @return bool
     */
    public function isAdminTokenSet()
    {
        return ($this->adminToken) ? true : false;
    }

    /**
     * Check whether admin token set or throw an exception
     *
     * @throws ConfigurationException
     */
    public function adminTokenSetOrFail()
    {
        if( ! $this->isAdminTokenSet()) {
            throw new ConfigurationException("missing admin token");
        }
      
        return $this;
    }
}