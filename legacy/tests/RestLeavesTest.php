<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RestLeavesTest extends TestCase
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
     * @covers RestLeaves::options
     * 
     */
    public function testPreflightCORS()
    {
        $response = $this->httpClient->request('OPTIONS', 'leaves', ['auth' => ['bbalet', 'bbalet']]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Methods'));
        $this->assertTrue($response->hasHeader('Access-Control-Allow-Headers'));
        $body = (string) $response->getBody();
        $this->assertEmpty($body);
    }

    /**
     * Creates a leave request
     * @covers RestLeaves::create
     * 
     */
    public function testCreateLeaveRequest()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['bbalet', 'bbalet'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "2018-07-21",
                    "status" => "1",
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
     * Test if the leave request was properly created
     * We don't send the preferred language, 
     * so US English formatting should be returned
     * @depends testCreateLeaveRequest
     * 
     */
    public function testViewLeaveRequest(int $leaveId)
    {
        $response = $this->httpClient->request('GET', 'leaves/' . $leaveId,
            ['auth' => ['bbalet', 'bbalet']]
        );
        $this->assertEquals(200, $response->getStatusCode());
        $leave = json_decode($response->getBody(true), true);

        $this->assertEquals($leaveId, $leave['id']);
        $this->assertEquals('07/21/2018', $leave['startdate']);    //English date formatting
        $this->assertEquals('07/21/2018', $leave['enddate']);    //English date formatting
        $this->assertEquals('1', $leave['status']);
        $this->assertEquals('1', $leave['employee']);
        $this->assertEquals('test REST API', $leave['cause']);
        $this->assertEquals('Morning', $leave['startdatetype']);
        $this->assertEquals('Afternoon', $leave['enddatetype']);
        $this->assertEquals('1.000', $leave['duration']);
        $this->assertEquals('1', $leave['type']);
        $this->assertEquals('Planned', $leave['status_name']);
        $this->assertEquals('paid leave', $leave['type_name']);
    }

    /**
     * A non admin user shouldn't be able to force the LR
     * status to something else than planned or requested
     * 
     */
    public function testForceStatus()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['jdoe', 'jdoe'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "2018-07-21",
                    "status" => "4",
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
        $response = $this->httpClient->request('GET', 'leaves/' . $leaveId,
            ['auth' => ['jdoe', 'jdoe']]
        );
        $this->assertEquals(200, $response->getStatusCode());
        $leave = json_decode($response->getBody(true), true);

        $this->assertEquals($leaveId, $leave['id']);
        $this->assertEquals('2', $leave['status']);
    }

    /**
     * Delete a LR and then try to display it again
     * @depends testCreateLeaveRequest
     * 
     */
    public function testDeleteLeaveRequests(int $leaveId)
    {
        $response = $this->httpClient->request('DELETE', 'leaves/' . $leaveId,
            ['auth' => ['bbalet', 'bbalet']]
        );
        $this->assertEquals(200, $response->getStatusCode());

        //Try to get the deleted object
        $response = $this->httpClient->request('GET', 'leaves/' . $leaveId,
            ['http_errors' => false,
                'auth' => ['bbalet', 'bbalet']
            ]);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Employee (non admin) must not be able to see the LR of another employee
     * 
     */
    public function testIllegalAccessToLeaveRequest()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['bbalet', 'bbalet'],
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

        $response = $this->httpClient->request('GET', 'leaves/' . $leaveId,
            ['http_errors' => false,
                'auth' => ['jdoe', 'jdoe']
            ]);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Try to display a LR that doesn't exist
     * 
     */
    public function testLeaveRequestNotFound()
    {
        //Try to get the deleted object
        $response = $this->httpClient->request('GET', 'leaves/999999',
            ['http_errors' => false,
                'auth' => ['jdoe', 'jdoe']
            ]);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Update a leave request with normal values
     * 
     */
    public function testEditLeaveRequest()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['bbalet', 'bbalet'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "2018-07-21",
                    "status" => "1",
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

        $response = $this->httpClient->request('PATCH', 'leaves/' . $leaveId,
            [
                'auth' => ['bbalet', 'bbalet'],
                'json' => [
                    "startdate" => "2018-08-27",
                    "enddate" => "2018-08-28",
                    "status" => "1",
                    "cause" => "test REST API - Update",
                    "startdatetype" => "Morning",
                    "enddatetype" => "Afternoon",
                    "duration" => "2.000",
                    "type" => "1"
                ]
            ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->httpClient->request('GET', 'leaves/' . $leaveId,
            ['auth' => ['bbalet', 'bbalet']]
        );
        $this->assertEquals(200, $response->getStatusCode());
        $leave = json_decode($response->getBody(true), true);

        $this->assertEquals($leaveId, $leave['id']);
        $this->assertEquals('08/27/2018', $leave['startdate']);    //English date formatting
        $this->assertEquals('08/28/2018', $leave['enddate']);    //English date formatting
        $this->assertEquals('1', $leave['status']);
        $this->assertEquals('1', $leave['employee']);
        $this->assertEquals('test REST API - Update', $leave['cause']);
        $this->assertEquals('Morning', $leave['startdatetype']);
        $this->assertEquals('Afternoon', $leave['enddatetype']);
        $this->assertEquals('2.000', $leave['duration']);
        $this->assertEquals('1', $leave['type']);
        $this->assertEquals('Planned', $leave['status_name']);
        $this->assertEquals('paid leave', $leave['type_name']);
    }

    /**
     * Tries to create a leave request with invalid data
     * @covers RestLeaves::create
     * 
     */
    public function testCreateLeaveRequestWithIllegalValues()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'http_errors' => false,
                'auth' => ['bbalet', 'bbalet'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "invalid",
                    "status" => "2",
                    "cause" => "test REST API",
                    "startdatetype" => "Morning",
                    "enddatetype" => "Afternoon",
                    "duration" => "1.000",
                    "type" => "1"
                ]
            ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Try to update the leave request of another employee
     * while non admin and non HR
     * 
     */
    public function testIllegalEditLeaveRequest()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['bbalet', 'bbalet'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "2018-07-21",
                    "status" => "1",
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

        $response = $this->httpClient->request('PATCH', 'leaves/' . $leaveId,
            [
                'http_errors' => false,
                'auth' => ['jdoe', 'jdoe'],
                'json' => [
                    "startdate" => "2018-08-27",
                    "enddate" => "2018-08-28",
                    "status" => "1",
                    "cause" => "test REST API - Update",
                    "startdatetype" => "Morning",
                    "enddatetype" => "Afternoon",
                    "duration" => "2.000",
                    "type" => "1"
                ]
            ]);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Try to update the leave request with invalid data
     * 
     */
    public function testEditLeaveRequestWithIllegalValues()
    {
        $response = $this->httpClient->request('POST', 'leaves',
            [
                'auth' => ['jdoe', 'jdoe'],
                'json' => [
                    "startdate" => "2018-07-21",
                    "enddate" => "2018-07-21",
                    "status" => "1",
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

        $response = $this->httpClient->request('PATCH', 'leaves/' . $leaveId,
            [
                'http_errors' => false,
                'auth' => ['jdoe', 'jdoe'],
                'json' => [
                    "startdate" => "2018-08-27",
                    "enddate" => "2018-08-28",
                    "status" => "1",
                    "cause" => "test REST API - Update",
                    "startdatetype" => "Morning",
                    "enddatetype" => "Afternoon",
                    "duration" => "invalid",
                    "type" => "1"
                ]
            ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Display the list of leave requests. We need at least one LR.
     * Test with another language than French
     * @depends testCreateLeaveRequest
     */
    public function testListOfMyLeaveRequests()
    {
        $response = $this->httpClient->request('GET', 'leaves', [
            'auth' => ['bbalet', 'bbalet'],
            'headers' => [
                'Accept-Language' => 'fr'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('startdate', $data[0]);
        $this->assertArrayHasKey('enddate', $data[0]);
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
        //Test if the language was properly negotiated to French
        $statusNames = array('Planifiée', 'Acceptée', 'Demandée', 'Rejetée', 'Annulation', 'Annulée');
        $this->assertContains($data[0]['status_name'], $statusNames);
    }
}
