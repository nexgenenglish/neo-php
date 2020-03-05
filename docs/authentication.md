# Authentication
Authentication module is implementation and API wrapper for SSO service.

#### Setup
First thing before using `Auth` module, you need to setup service HTTP Client. Neo PHP ships `GuzzleHttpClient` as default HTTP Client using GuzzleHttp implementation. In case of Auth module used before HTTP Client set up, an configuration exception (`Neo\Exceptions\ConfigurationException`) will thrown.

```php
<?php 

require "vendor/autoload.php";

use Neo\Auth\Auth;
use Neo\HttpClients\GuzzleHttpClient;

// Setup HTTP client
$httpClient = new GuzzleHttpClient([
    'base_uri' => "https://domain.com"
]);

// Setup Auth using GuzzleHttpClient implementation
$auth = new Auth($httpClient);

// Now, you can use $auth
```

Feel free to using your own HTTP Client implementation as necessary. You may need to implement the `Neo\HttpClients\HttpClientInterface` and write code for the `get`, `post`, `put`, `patch`, and `delete` method in your custom implementation. The new custom HTTP Client would look something like this:

```php
<?php

use Neo\HttpClients\HttpClientInterface;

class CustomHttpClient implements HttpClientInterface {
    public function get($uri, array $options = []) { /* Implement */ }
    public function post($uri, array $options = [])  { /* Implement */ }
    public function put($uri, array $options = [])  { /* Implement */ }
    public function patch($uri, array $options = [])  { /* Implement */ }
    public function delete($uri)  { /* Implement */ }
}
```
#### Setup - Auth Proxy (version v0.5.0+)
There are cases in authentication when the credentials is known but nothing about the server. To solve this issue, now neo-php has AuthProxy to guess credentials for each given server.
 
 ```php
<?php 
 
require "vendor/autoload.php";

use Neo\Auth\AuthProxy;
use Neo\HttpClients\GuzzleHttpClient;

// Setup HTTP client for server A
$httpClientServerA = new GuzzleHttpClient([
 'base_uri' => "https://a.domain.com"
]);

// Setup HTTP client for server B
$httpClientServerB = new GuzzleHttpClient([
    'base_uri' => "https://b.domain.com"
]);
 
// Setup Auth using GuzzleHttpClient implementation
$auth = new AuthProxy([
    'server_a' => $httpClientServerA,
    'server_b' => $httpClientServerB
]);
 
// Now, you can use $auth as usual.

// Retrieve which server recognized the credential
// Should be server_a or server_b if credential recognised
// Otherwise return "" (empty string)
$server = $auth->getLastServerFound();  
 ```

To retrieve the server that successfully recognise credentials, you can call `$auth->getLastServerFound()` after perform AuthProxy action. An empty string returned when the

#### Token
Token retrieves JSON Web Token (JWT) from SSO service based on given credential. This method accept credential in array and consist of `username` and `password` keys. In case of credential is missing, an validation exception (`Neo\Exceptions\ValidationException`) thrown. This method return Token (`Neo\Auth\Token`) type.

```php
<?php

use Neo\Auth\Auth;
use Neo\HttpClients\GuzzleHttpClient;

// Setup HTTP client
$httpClient = new GuzzleHttpClient([
    'base_uri' => "https://domain.com"
]);

// Setup Auth using GuzzleHttpClient implementation
$auth = new Auth($httpClient);

$token = $auth->token([
    'username' => 'username',
    'password' => 'password'
]);

// Printing token using magic method __toString or casting method
echo $token;
echo $token->string();

// Retrieve JWT token decoded
// The returned data is in collection (\Tightenco\Collect\Support\Collection)
// Which is same collection with Laravel using
$parsed = $token->parse();

// Get token username from payload
echo $parsed->get('payload')->username;
```

#### Verify
Sometimes, you want to verify existing token to SSO service. To do that you may call `verify` method and pass the token (`Neo\Auth\Token`) to verify. The method will return boolean whether token is valid or not.

```php
<?php

use Neo\Auth\Auth;

// Setup Auth HttpClient and retrieve token from any source

$valid = $auth->verify($token);
echo ($valid) ? "Valid" : "Invalid";
```

#### User
If you have token (`Neo\Auth\Token`) and want to retrieve the user ACL and profile information, you may using `user` method. This method accept token (`Neo\Auth\Token`) and will return user's ACL and profile.

 ```php
<?php

use Neo\Auth\Auth;

// Setup Auth HttpClient and retrieve token from any source

$user = $auth->user($token);
var_dump($user->acl);
var_dump($user->profile);
```

 
#### Login
A bit different with others method, `login` return User (`Neo\Auth\User`) by passing credential. This method return user with more information such as token (`Neo\Auth\Token`), ACL and profile.

 ```php
<?php

use Neo\Auth\Auth;

// Setup Auth HttpClient and retrieve token

$user = $auth->login([
    'username' => 'username',
    'password' => 'password'
]);

// Retrieve user's token
echo $user->token();

// Retrieve user's profile collection
var_dump($user->profile());
echo $user->profile()->get('roles')[0];
echo $user->profile("roles")[0];

// Retrieve user's ACL collection
var_dump($user->acl());
```
 
### Feature Request
If you have any feature request to Auth module, feel free to open issue in this GitHub projects.
