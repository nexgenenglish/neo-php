<?php

use PHPUnit\Framework\TestCase;
use DynEd\Neo\Auth\Auth;
use DynEd\Neo\HttpClients\GuzzleHttpClient;
use DynEd\Neo\Study\Admin;

class StudyAdminTest extends TestCase
{
    protected $ssoBaseUri;
    protected $ssoUsername;
    protected $ssoPassword;

    public function setUp(): void
    {
        parent::setUp();

        $this->ssoBaseUri = getenv("NEO_SSO_BASE_URI");
        $this->ssoUsername = getenv("NEO_SSO_USERNAME");
        $this->ssoPassword = getenv("NEO_SSO_PASSWORD");

        Auth::useHttpClient(new GuzzleHttpClient([
            'base_uri' => getenv("NEO_SSO_BASE_URI")
        ]));

        Admin::useHttpClient(new GuzzleHttpClient([
                'base_uri' => getenv("NEO_SSO_BASE_URI")
        ]));
    }

    public function testStudyAdminStudentsSummaryOrganisation()
    {
        $adminToken = Auth::token([
            'username' => $this->ssoUsername,
            'password' => $this->ssoPassword
        ]);

        Admin::setAdminToken($adminToken);

        $students = Admin::studentsSummaryOrganisation('001');

        $this->assertNotNull($students);
    }

    public function testStudyAdminStudentSummaryPeriod()
    {
        $adminToken = Auth::token([
            'username' => $this->ssoUsername,
            'password' => $this->ssoPassword
        ]);

        Admin::setAdminToken($adminToken);

        $students = Admin::studentsSummaryOrganisation('001');

        $this->assertNotNull($students->data[0]->username);

        $summary = Admin::studentSummaryPeriod($students->data[0]->username, ['start' => '2018-01-01', 'end' => '2020-01-01']);

        $this->assertNotNull($summary);
    }

}


