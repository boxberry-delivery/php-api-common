<?php


namespace Boxberry\Common\Http;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Клиент для запросов
 *
 * @package Boxberry\Common\Http
 */
class Client implements RequestFactory
{
    /**
     * PSR-7 HttpClient, реализующий `public function sendRequest(RequestInterface $request)`
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * PSR-7 RequestFactory
     *
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @param null                $httpClient
     * @param RequestFactory|null $requestFactory
     */
    public function __construct($httpClient = null, RequestFactory $requestFactory = null)
    {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * Отправка GET-запроса
     *
     * @param UriInterface|string $uri
     * @param array               $headers
     *
     * @return ResponseInterface
     */
    public function get($uri, array $headers = [])
    {
        return $this->send('GET', $uri, $headers);
    }

    /**
     * Отправка запроса
     *
     * @param                                            $method
     * @param                                            $uri
     * @param array                                      $headers
     * @param string|array|resource|StreamInterface|null $body
     * @param string                                     $protocolVersion
     *
     * @return ResponseInterface
     */
    public function send($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        if (is_array($body)) {
            $body = http_build_query($body, '', '&');
        }
        $request = $this->createRequest($method, $uri, $headers, $body, $protocolVersion);

        return $this->sendRequest($request);
    }

    /**
     * @param                                      $method
     * @param                                      $uri
     * @param array                                $headers
     * @param string|resource|StreamInterface|null $body
     * @param string                               $protocolVersion
     *
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return $this->requestFactory->createRequest($method, $uri, $headers, $body, $protocolVersion);
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Http\Client\Exception
     */
    public function sendRequest(RequestInterface $request)
    {
        return $this->httpClient->sendRequest($request);
    }

    /**
     * Отправка POST-запроса
     *
     * @param UriInterface|string                        $uri
     * @param array                                      $headers
     * @param string|array|null|resource|StreamInterface $body
     *
     * @return ResponseInterface
     */
    public function post($uri, array $headers = [], $body = null)
    {
        return $this->send('POST', $uri, $headers, $body);
    }
}
