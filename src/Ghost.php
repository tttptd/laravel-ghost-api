<?php

namespace Tttptd\GhostAPI;

use Tttptd\GhostAPI\Providers\PageProvider;
use Tttptd\GhostAPI\Providers\PostProvider;
use Tttptd\GhostAPI\Providers\TagProvider;
use Tttptd\GhostAPI\Providers\UserProvider;
use GuzzleHttp\Client;

class Ghost
{

    /**
     * Blog Base Uri
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $key;

    /**
     * The Guzzle client
     * @var Client
     */
    protected $httpClient;

    /**
     * Ghost constructor.
     */
    public function __construct()
    {
        $this->baseUri = config('ghost.base_uri');
        $this->key = config('ghost.key');

        $this->httpClient = new Client([
            'base_uri' => rtrim($this->baseUri, '/') . '/ghost/api/v2/content/',
        ]);
    }

    /**
     * Sends an http request
     * @param string $endpoint The service to request
     * @param array  $options  The options of the request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($endpoint, $options):array
    {
        $data = [];

        // dd($endpoint, $options);

        // try
        // {
        // default headers
        $options['headers']['Content-Type'] = 'application/json';

        // auth data for public API
        $options['query']['key'] = $this->key;

        // do a request
        $response = $this->httpClient->request('GET', $endpoint, $options);
        $data = json_decode($response->getBody()->getContents(), true);
        // }
        // catch (RequestException $e)
        // {
        //
        // }

        return $data;
    }

    public function posts():PostProvider
    {
        return new PostProvider($this);
    }

    public function post():PostProvider
    {
        return new PostProvider($this);
    }

    public function tags():TagProvider
    {
        return new TagProvider($this);
    }

    public function users():UserProvider
    {
        return new UserProvider($this);
    }

    public function page():PageProvider
    {
        return new PageProvider($this);
    }

}
