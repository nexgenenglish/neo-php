<?php

use Neo\Auth\AuthProxy as Auth;
use Neo\Auth\Token;
use Neo\Exceptions\ValidationException;
use Neo\HttpClients\GuzzleHttpClient;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

class AuthProxyTest extends TestCase
{
    protected $baseUriGlobal;
    protected $usernameGlobal;
    protected $passwordGlobal;
    protected $baseUriChina;
    protected $usernameChina;
    protected $passwordChina;
    protected $authProxy;

    public function setUp(): void
    {
        parent::setUp();

        $this->baseUriGlobal = getenv('NEO_SSO_BASE_URI_GLOBAL');
        $this->baseUriChina = getenv('NEO_SSO_BASE_URI_CHINA');
        $this->usernameGlobal = getenv('NEO_SSO_USERNAME_GLOBAL');
        $this->passwordGlobal = getenv('NEO_SSO_PASSWORD_GLOBAL');
        $this->usernameChina = getenv('NEO_SSO_USERNAME_CHINA');
        $this->passwordChina = getenv('NEO_SSO_PASSWORD_CHINA');

        $httpClientGlobal = new GuzzleHttpClient([
            'base_uri' => getenv('NEO_SSO_BASE_URI_GLOBAL'),
            'timeout'  => 10,
        ]);

        $httpClientChina = new GuzzleHttpClient([
            'base_uri' => getenv('NEO_SSO_BASE_URI_CHINA'),
            'timeout'  => 10,
        ]);

        $this->authProxy = new Auth([
            'global' => $httpClientGlobal,
            'china'  => $httpClientChina,
        ]);
    }

    public function testAuthTokenValidation_EmptyCredential()
    {
        $this->expectException(
            ValidationException::class
        );

        $this->authProxy->token([]);
    }

    public function testAuthTokenValidation_InvalidUsername()
    {
        $token = $this->authProxy->token([
            'username' => 'invalid',
            'password' => 'password',
        ]);

        $this->assertNull($token);
    }

    public function testAuthTokenValidation_PasswordNotMatch()
    {
        $token = $this->authProxy->token([
            'username' => $this->usernameChina,
            'password' => 'invalid',
        ]);

        $this->assertNull($token);
    }

    public function testAuthTokenCredential()
    {
        // Global
        $token = $this->authProxy->token([
            'username' => $this->usernameGlobal,
            'password' => $this->passwordGlobal,
        ]);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertIsString($token->string());
        $this->assertEquals('global', $this->authProxy->getLastServerFound());

        // China
        $token = $this->authProxy->token([
            'username' => $this->usernameChina,
            'password' => $this->passwordChina,
        ]);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertIsString($token->string());
        $this->assertEquals('china', $this->authProxy->getLastServerFound());
    }

    public function testAuthTokenVerify_Invalid()
    {
        $valid = $this->authProxy->verify(
            new Token('invalid')
        );

        $this->assertFalse($valid);
    }

    public function testAuthTokenVerify_Valid()
    {
        $token = $this->authProxy->token([
            'username' => $this->usernameGlobal,
            'password' => $this->passwordGlobal,
        ]);

        $valid = $this->authProxy->verify($token);

        $this->assertTrue($valid);

        $token = $this->authProxy->token([
            'username' => $this->usernameChina,
            'password' => $this->passwordChina,
        ]);

        $valid = $this->authProxy->verify($token);

        $this->assertTrue($valid);
    }

    public function testAuthUser()
    {
        $token = $this->authProxy->token([
            'username' => $this->usernameGlobal,
            'password' => $this->passwordGlobal,
        ]);

        $user = $this->authProxy->user($token);

        $this->assertObjectHasAttribute('acl', $user);
        $this->assertObjectHasAttribute('profile', $user);
    }

    public function testAuthLogin()
    {
        $user = $this->authProxy->login([
            'username' => $this->usernameGlobal,
            'password' => $this->passwordGlobal,
        ]);

        $this->assertTrue($this->authProxy->verify($user->token()));
        $this->assertInstanceOf(Token::class, $user->token());
        $this->assertInstanceOf(Collection::class, $user->acl());
        $this->assertInstanceOf(Collection::class, $user->profile());
    }
}
