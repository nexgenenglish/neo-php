<?php

namespace Neo\Interim;

use Neo\AbstractApi;
use Neo\AuthorizationTrait;
use Neo\Exceptions\ValidationException;
use Rakit\Validation\Validator;

class Applicant extends AbstractApi
{
    use AuthorizationTrait;

    /**
     * Configure default value.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->endpoints = [
            'register.password'  => 'api/neo/register/password',
        ];
    }

    /**
     * Register an applicant.
     *
     * @param $email
     * @param array $payload
     *
     * @throws \Neo\Exceptions\ConfigurationException
     *
     * @return mixed|void
     */
    public function register($email, array $payload)
    {
        $this->httpClientSetOrFail()
            ->authorizationTokenSetOrFail();

        // TODO: complete validation rules
//        $validation = (new Validator())->validate($payload, [
//            'fullname' => 'required',
//            'dial_code' => 'required',
//        ]);
//
//        if ($validation->fails()) {
//            throw new ValidationException('missing credential username or password');
//        }

        $response = $this->httpClient->post($this->getEndpoints('register.password'),
            [
                'form_params' => [
                    'email'   => $email,
                    'payload' => $payload,
                ],
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => $this->getAuthorizationToken(),
                ],
            ]
        );

        if ($response->getStatusCode() != '200') {
            return;
        }

        $raw = $response->getBody()->getContents();

        if ($this->getConfig('raw_response')) {
            return $raw;
        }

        return json_decode($raw);
    }
}
