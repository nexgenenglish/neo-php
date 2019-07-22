<?php

namespace Neo\Etest;

use Neo\AbstractApi;
use Neo\AdminTokenTrait;
use Tightenco\Collect\Support\Collection;

class Bank extends AbstractApi
{
    use AdminTokenTrait;

    /**
     * Configure default value
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->endpoints = [
            'questions' => '/banks/question'
        ];
    }

    /**
     * Retrieve questions
     *
     * @return Collection|null
     * @throws \Neo\Exceptions\ConfigurationException
     */
    public function questions()
    {
        $this->httpClientSetOrFail()->adminTokenSetOrFail();

        $response = $this->httpClient->get($this->getEndpoints('questions'),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-DynEd-Tkn' => $this->adminToken->string()
                ]
            ]
        );

        if ($response->getStatusCode() != '200') {
            return null;
        }

        $raw = $response->getBody()->getContents();

        if($this->getConfig('raw_response')) {
            return $raw;
        }

        $questions = [];
        $qs = json_decode($raw, true);

        foreach ($qs as $q) {
            $questions[] = new Question($q);
        }

        return collect($questions);
    }
}