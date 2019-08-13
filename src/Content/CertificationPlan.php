<?php

namespace Neo\Content;

class CertificationPlan
{
    /**
     * Name
     *
     * @var string
     */
    public $name;

    /**
     * Checksum
     *
     * @var string
     */
    public $checksum;

    /**
     * Download URL
     *
     * @var string
     */
    public $downloadUrl;

    /**
     * Updated At
     *
     * @var string
     */
    public $updatedAt;

    /**
     * Create
     *
     * @param array $data
     * @return CertificationPlan
     */
    public static function create(array $data)
    {
        $cp = new self();

        foreach ($data as $key => $value) {
            $cp->{$key} = $value;
        }

        return $cp;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Checksum
     *
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Get Download URL
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        return $this->downloadUrl;
    }

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}