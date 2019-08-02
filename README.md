# PHP Library for Neo API Ecosystem 
[![Build Status](https://travis-ci.org/nexgenenglish/neo-php.svg?branch=master)](https://travis-ci.org/nexgenenglish/neo-php)

#### Installation

The easiest way to install neo-php library is using composer

```
composer require nexgenenglish/neo-php
```

That's it!

#### Documentations
Please refer to [documentations](docs) folder.


#### Test
Run the PHPUnit test using PHPUnit.

```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests --do-not-cache-result
```

The test reads several environment variable, such as service configuration, you need to provide: `NEO_SSO_BASE_URI`, `NEO_SSO_USERNAME`, `NEO_SSO_PASSWORD`, `NEO_ETEST_BASE_URI`. In your macOS, you can use export command

```
export NEO_SSO_BASE_URI="https://sso.myneo.space"
export NEO_SSO_USERNAME="username"
export NEO_SSO_PASSWORD="password"
export NEO_ETEST_BASE_URI='https://etest.myneo.space'
```
