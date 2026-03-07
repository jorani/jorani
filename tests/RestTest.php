<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RestTest extends TestCase
{
    private $httpClient;

    /**
     * Create a common HTTP client for all test cases pointing to 
     * the API URL defined as environment variable (or by phpunit.xml)
     * 
     */
    public function setUp()
    {
        $this->httpClient = new GuzzleHttp\Client(
            ['base_uri' => $_ENV['TEST_API_BASE_URL']]
        );
    }

    /**
     * Free resources after this test case
     * 
     */
    public function tearDown()
    {
        $this->httpClient = null;
    }

    /**
     * Test if preflight HTTP queries (for CORS) are working
     * It should be inerited from MY_RestController::options
     * But relying of what is set into the parent's constructor
     * @covers Rest::options
     * 
     */
    public function testPreflightCORS()
    {
        $response = $this->httpClient->request('OPTIONS', 'config', ['auth' => ['bbalet', 'bbalet']]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Methods'));
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Headers'));
        $body = (string) $response->getBody();
        $this->assertEmpty($body);
    }

    /**
     * Get the configuration of Jorani
     * @covers Rest::config
     */
    public function testGetConfig()
    {
        $response = $this->httpClient->request('GET', 'config', [
            'auth' => ['bbalet', 'bbalet'],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true));
        $this->assertObjectHasAttribute('IsOvertimeDisabled', $data);
        $this->assertObjectHasAttribute('IsHistoryEnabled', $data);
        $this->assertObjectHasAttribute('IsPublicCalendarEnabled', $data);
        $this->assertObjectHasAttribute('IsIcsEnabled', $data);
        $this->assertObjectHasAttribute('hideGlobalCalsToUsers', $data);
        $this->assertObjectHasAttribute('disableDepartmentCalendar', $data);
        $this->assertObjectHasAttribute('disableWorkmatesCalendar', $data);
        $this->assertObjectHasAttribute('disallowRequestsWithoutCredit', $data);
        $this->assertObjectHasAttribute('mandatoryCommentOnReject', $data);
        $this->assertObjectHasAttribute('requestsByManager', $data);
        $this->assertObjectHasAttribute('extraStatusRequested', $data);
        $this->assertObjectHasAttribute('disableEditLeaveDuration', $data);
        $this->assertObjectHasAttribute('versionOfJorani', $data);
    }

    /**
     * Get basic details about connected user
     * @covers Rest::self
     */
    public function testGetSelf()
    {
        $response = $this->httpClient->request('GET', 'self', [
            'auth' => ['bbalet', 'bbalet'],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true));
        $this->assertObjectHasAttribute('isAdmin', $data);
        $this->assertObjectHasAttribute('isHr', $data);
        $this->assertObjectHasAttribute('isManager', $data);
        $this->assertObjectHasAttribute('login', $data);
        $this->assertObjectHasAttribute('id', $data);
        $this->assertObjectHasAttribute('firstname', $data);
        $this->assertObjectHasAttribute('lastname', $data);
        $this->assertObjectHasAttribute('manager', $data);
        $this->assertObjectHasAttribute('email', $data);
        $this->assertObjectHasAttribute('contract', $data);
        $this->assertObjectHasAttribute('position', $data);
        $this->assertObjectHasAttribute('organization', $data);
    }

    /**
     * Get the profile of the connected user
     * @covers Rest::profile
     */
    public function testGetProfile()
    {
        $response = $this->httpClient->request('GET', 'profile', [
            'auth' => ['bbalet', 'bbalet'],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true));
        $this->assertObjectHasAttribute('managerName', $data);
        $this->assertObjectHasAttribute('contractName', $data);
        $this->assertObjectHasAttribute('positionName', $data);
        $this->assertObjectHasAttribute('organizationName', $data);
    }

    /**
     * Get the number of submissions sent to the connected user
     * @covers Rest::submissions
     */
    public function testGetSubmissions()
    {
        $response = $this->httpClient->request('GET', 'submissions', [
            'auth' => ['bbalet', 'bbalet'],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true));
        $this->assertObjectHasAttribute('isOvertimeDisabled', $data);
        $this->assertObjectHasAttribute('requestedLeavesCount', $data);
        $this->assertObjectHasAttribute('requestedExtrasCount', $data);
        $this->assertObjectHasAttribute('requestsTotal', $data);
        $count = $data->requestedLeavesCount + $data->requestedExtrasCount;
        $this->assertEquals($count, $data->requestsTotal);
    }

    /**
     * Get the number of submissions sent to the connected user
     * @covers Rest::checksum
     */
    public function testGetChecksum()
    {
        //Get checksum of all tables
        $response = $this->httpClient->request('GET', 'checksum', [
            'auth' => ['bbalet', 'bbalet'],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true));
        $this->assertObjectHasAttribute('leaves', $data);
        $this->assertObjectHasAttribute('users', $data);
        $checksumLeavesTable = $data->leaves;

        //Create a leave request so as to alter the checksum
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['jdoe', 'jdoe'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "2018-07-21",
                    "status" => "2",
                    "cause" => "test REST API",
                    "startdatetype" => "Morning",
                    "enddatetype" => "Afternoon",
                    "duration" => "1.000",
                    "type" => "1"
                ]
            ]);
        $this->assertEquals(200, $response->getStatusCode());

        //Get checksum of the leave requests table only
        $response = $this->httpClient->request('GET', 'checksum/leaves', [
            'auth' => ['bbalet', 'bbalet'],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true));
        $this->assertObjectHasAttribute('leaves', $data);
        $this->assertObjectNotHasAttribute('users', $data);
        $this->assertNotEquals($checksumLeavesTable, $data->leaves);
    }
}
