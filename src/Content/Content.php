<?php

namespace Neo\Content;

use Neo\AbstractApi;

class Content extends AbstractApi
{
    /**
     * Configure default value.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->endpoints = [
            'list'  => '/api/v1/dsa_flow_list',
        ];
    }

    /**
     * Get certification plans
     *
     * @throws \Neo\Exceptions\ConfigurationException
     */
    public function getCertificationPlans()
    {
        $this->httpClientSetOrFail();

        $response = $this->httpClient->get($this->getEndpoints('list'));

        if ($response->getStatusCode() != '200') {
            return;
        }

        $raw = $response->getBody()->getContents();

        if ($this->getConfig('raw_response')) {
            return $raw;
        }

        $result = [];
        $cp = json_decode($raw);

        foreach ($cp as $item) {
            $result[] = CertificationPlan::create([
                'name' => $item->cert_plan,
                'checksum' => $item->sha1,
                'downloadUrl' => $item->download_url,
                'updatedAt' => $item->last_update
            ]);
        }

        return collect($result);
    }
}