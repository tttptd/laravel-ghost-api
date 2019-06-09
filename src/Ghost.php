<?php

namespace Tttptd\GhostAPI;

use Ahc\Jwt\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Tttptd\GhostAPI\Providers\PageProvider;
use Tttptd\GhostAPI\Providers\PostProvider;
use Tttptd\GhostAPI\Providers\SubscriptionProvider;
use Tttptd\GhostAPI\Providers\TagProvider;
use Tttptd\GhostAPI\Providers\UserProvider;
use function json_decode;
use function rtrim;

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
     * @var Client
     */
    protected $adminHttpClient;

    /**
     * @var string
     */
    protected $adminKey;

    /**
     * Ghost constructor.
     */
    public function __construct()
    {
        $this->baseUri = config('ghost.base_uri');
        $this->key = config('ghost.key');
        $this->adminKey = config('ghost.adminKey');

        $this->httpClient = new Client([
            'base_uri' => rtrim($this->baseUri, '/') . '/ghost/api/v2/content/',
        ]);

        $this->adminHttpClient = new Client([
            'base_uri' => rtrim($this->baseUri, '/') . '/ghost/api/v2/admin/',
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

    /**
     * @param        $endpoint
     * @param        $bodyData
     * @param string $method
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestAdmin($endpoint, $bodyData, string $method = 'GET')
    {
        $result = [];
        $options = [];

        // try {
            // Подпишем запрос JWT
            [$id, $secret] = explode(':', $this->adminKey);
            // Результат hex2bin() не подходит
            $secret = sodium_hex2bin($secret);
            $jwt = new JWT($secret, 'HS256', 300);
            $jwt->registerKeys([$id => $secret]);

            $token = $jwt->encode([
                'iat' => time(),
                'exp' => time() + 300,
                'aud' => '/v2/admin/',
            ], ['kid' => $id]);

            $options['headers']['Authorization'] = 'Ghost ' . $token;

            $options['form_params'] = $bodyData;

            $response = $this->adminHttpClient->request($method, $endpoint, $options);
            $result = json_decode($response->getBody()->getContents(), true);
        // } catch(RequestException $e) {
        //
        // }

        return $result;
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

    public function subscription():SubscriptionProvider
    {
        return new SubscriptionProvider($this);
    }

}
