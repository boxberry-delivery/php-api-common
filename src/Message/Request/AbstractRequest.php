<?php


namespace Dumkaaa\Boxberry\Api\Common\Message\Request;

use Dumkaaa\Boxberry\Api\Common\Exception\RuntimeException;
use Dumkaaa\Boxberry\Api\Common\Helper;
use Dumkaaa\Boxberry\Api\Common\Http\Client;
use Dumkaaa\Boxberry\Api\Common\Message\RequestInterface;
use Dumkaaa\Boxberry\Api\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Абстрактный класс запроса
 *
 * @package Dumkaaa\Boxberry\Api\Common\Message\Request
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Параметры запроса
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * Валидатор параметров запроса
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * HttpClient, отправляющий запросы
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * HTTP запрос
     *
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * Полученный ответ
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param Client      $httpClient
     * @param HttpRequest $httpRequest
     */
    public function __construct(Client $httpClient, HttpRequest $httpRequest)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
        $this->initialize();
    }

    /**
     * @inheritdoc
     * @throws RuntimeException
     */
    public function initialize(array $parameters = [])
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }
        $this->parameters = new ParameterBag;
        $this->validator = Validation::createValidator();
        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * @inheritdoc
     * @throws \ReflectionException
     */
    public function send()
    {
        $this->validateData();
        $data = $this->getPreparedData();

        return $this->sendData($data);
    }

    /**
     * Валидация параметров запроса
     *
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function validateData()
    {
        $constraint = new Assert\Collection([
            'fields' => $this->getValidationRules(),
        ]);

        $violations = $this->validator->validate($this->getParameters(), $constraint);
        if ($violations->count()) {
            throw new ValidatorException($violations);
        }

        return $this;
    }

    /**
     * Возвращает правила валидации
     *
     * @return array
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function getValidationRules()
    {
        return [
            'endPoint'  => new Assert\Required([
                new Assert\NotNull(),
                new Assert\Type(['type' => 'string']),
            ]),
            'api_token' => new Assert\Required([
                new Assert\NotNull(),
                new Assert\Type(['type' => 'string']),
            ]),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Возвращает поодготовленные к отправке параметры запроса
     *
     * @return array
     */
    public function getPreparedData()
    {
        return array_merge([
            'api_token' => $this->getToken(),
            'method'    => $this->getMethod(),
        ], $this->getParameters());
    }

    /**
     * Возвращает токен api boxberry
     *
     * @return string
     */
    abstract public function getToken();

    /**
     * Возвращает метод api boxberry
     *
     * @return string
     */
    abstract public function getMethod();

    /**
     * @inheritdoc
     * @throws RuntimeException
     * @throws \ReflectionException
     */
    public function sendData($data)
    {
        if ($this->getHttpMethod() === 'GET') {
            $httpResponse = $this->httpClient->send(
                $this->getHttpMethod(),
                $this->getEndPoint() . '?' . http_build_query($data),
                $this->getHeaders()
            );
        } else {
            $httpResponse = $this->httpClient->send(
                $this->getHttpMethod(),
                $this->getEndPoint(),
                $this->getHeaders(),
                $data
            );
        }
        $responseClassName = str_replace("Request", 'Response', get_class($this));
        $reflection = new \ReflectionClass($responseClassName);
        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                "Class $responseClassName not found"
            );
        }

        return $reflection->newInstance($this, json_decode($httpResponse->getBody(), true));
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->getParameter('endPoint');
    }

    /**
     * Возвращает параметр запроса
     *
     * @param string $key The parameter key
     *
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Возвращает заголовки запроса
     *
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }

    /**
     * @param $endPoint
     *
     * @return AbstractRequest
     */
    public function setEndPoint($endPoint)
    {
        return $this->setParameter('endPoint', $endPoint);
    }

    /**
     * Устанавливает параметр запроса
     *
     * @param $key
     * @param $value
     *
     * @return $this
     * @throws RuntimeException
     */
    protected function setParameter($key, $value)
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }
        $this->parameters->set($key, $value);

        return $this;
    }

    abstract public function setToken($token);

    /**
     * @inheritdoc
     */
    public function getResponse()
    {
        if (null === $this->response) {
            throw new RuntimeException('You must call send() before accessing the Response!');
        }

        return $this->response;
    }
}
