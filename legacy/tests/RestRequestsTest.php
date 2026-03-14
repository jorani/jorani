<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RestRequestsTest extends TestCase
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
     * @covers RestRequests::options
     * 
     */
    public function testPreflightCORS()
    {
        $response = $this->httpClient->request('OPTIONS', 'requests', ['auth' => ['bbalet', 'bbalet']]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Methods'));
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Headers'));
        $body = (string) $response->getBody();
        $this->assertEmpty($body);
    }

    /**
     * Creates a leave request (jdoe to his manager bbalet)
     * @covers RestRequests::requests
     * 
     */
    public function testCreateLeaveRequestForManager()
    {
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
        $body = (string) $response->getBody();
        $leaveId = intval(json_decode($body));
        return $leaveId;
    }

    /**
     * Test if the list of leave requests is empty for an employee that
     * is not a manager.
     * @covers RestLeaves::requests
     * @depends testCreateLeaveRequestForManager
     */
    public function testSubmittedLeaveRequestsIsEmptyForEmployee()
    {
        $response = $this->httpClient->request('GET', 'requests', [
            'auth' => ['jdoe', 'jdoe']
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertEmpty($data);
    }

    /**
     * Display the list of leave requests. We need at least one LR.
     * Test with another language than French
     * @covers RestLeaves::requests
     * @depends testCreateLeaveRequestForManager
     */
    public function testListOfSubmittedLeaveRequests()
    {
        $response = $this->httpClient->request('GET', 'requests', [
            'auth' => ['bbalet', 'bbalet'],
            'headers' => [
                'Accept-Language' => 'fr'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('leave_id', $data[0]);
        $this->assertArrayHasKey('firstname', $data[0]);
        $this->assertArrayHasKey('lastname', $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
        $this->assertArrayHasKey('employee', $data[0]);
        $this->assertArrayHasKey('cause', $data[0]);
        $this->assertArrayHasKey('startdatetype', $data[0]);
        $this->assertArrayHasKey('enddatetype', $data[0]);
        $this->assertArrayHasKey('duration', $data[0]);
        $this->assertArrayHasKey('type', $data[0]);
        $this->assertArrayHasKey('comments', $data[0]);
        $this->assertArrayHasKey('status_name', $data[0]);
        $this->assertArrayHasKey('type_name', $data[0]);
        //Let's not expose too much of data
        $this->assertArrayNotHasKey('login', $data[0]);
        $this->assertArrayNotHasKey('password', $data[0]);
        $this->assertArrayNotHasKey('role', $data[0]);
        $this->assertArrayNotHasKey('random_hash', $data[0]);
        //Test if the language was properly negotiated to French
        $statusNames = array('Planifiée', 'Acceptée', 'Demandée', 'Rejetée', 'Annulation', 'Annulée');
        $this->assertContains($data[0]['status_name'], $statusNames);
    }
}
