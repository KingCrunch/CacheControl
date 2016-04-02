<?php
namespace Crunch\CacheControl;

use Crunch\FastCGI\Client;
use Crunch\FastCGI\Connection;
use Crunch\FastCGI\Response;

class Connector
{
    /**
     * @var Connection
     */
    protected $connection;
    public function __construct (Client $client)
    {
        $this->connection = $client->connect();
    }

    public function clearCache ()
    {
        return $this->query('clear');
    }

    public function fetchStatus ()
    {
        return $this->query('status');
    }

    protected function query ($action)
    {
        $temporaryCheckout = sys_get_temp_dir() . '/' . uniqid("cache-control.$action.") . '.php';
        copy(__DIR__ . "/Resources/$action.php", $temporaryCheckout);

        $request = $this->connection->newRequest([
            'GATEWAY_INTERFACE' => 'FastCGI/1.0',
            'REQUEST_METHOD'    => 'GET',
            'SCRIPT_FILENAME'   => $temporaryCheckout
        ]);
        /** @var Response $response */
        $response = $this->connection->request($request);

        list ($header, $content) = explode("\r\n\r\n", $response->content, 2);

        unlink($temporaryCheckout);

        return unserialize($content);
    }
}
