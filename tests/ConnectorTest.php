<?php
namespace Crunch\CacheControl;

use Crunch\FastCGI\Client;
use Crunch\FastCGI\Connection;
use Crunch\FastCGI\Request;
use Crunch\FastCGI\Response;
use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Phake_IMock as Mock;

class ConnectorTest extends TestCase
{
    /** @var Client|Mock */
    private $fastCgiClient;

    /** @var Connection|Mock */
    private $fastCgiConnection;

    protected function setUp()
    {
        parent::setUp();
        $this->fastCgiClient = Phake::mock(Client::class);
        $this->fastCgiConnection = Phake::mock(Connection::class);

        Phake::when($this->fastCgiClient)->connect()->thenReturn($this->fastCgiConnection);
        Phake::when($this->fastCgiConnection)->newRequest(Phake::anyParameters())->thenCallParent();
    }

    public function testStatusRequest()
    {
        $response = new Response();
        $response->error = '';
        $response->content = "Some header\r\n\r\n" . serialize([]);

        Phake::when($this->fastCgiConnection)->request(Phake::anyParameters())->thenReturn($response);

        $connector = new Connector($this->fastCgiClient);

        $connector->fetchStatus();

        /** @var Request $request */
        $request = null;
        Phake::verify($this->fastCgiConnection, Phake::times(1))->request(Phake::capture($request));

        $this->assertContains('cache-control.status', $request->parameters['SCRIPT_FILENAME']);
    }

    public function testClearRequest()
    {
        $response = new Response();
        $response->error = '';
        $response->content = "Some header\r\n\r\n" . serialize([]);

        Phake::when($this->fastCgiConnection)->request(Phake::anyParameters())->thenReturn($response);

        $connector = new Connector($this->fastCgiClient);

        $connector->clearCache();

        /** @var Request $request */
        $request = null;
        Phake::verify($this->fastCgiConnection, Phake::times(1))->request(Phake::capture($request));

        $this->assertContains('cache-control.clear', $request->parameters['SCRIPT_FILENAME']);
    }
}
