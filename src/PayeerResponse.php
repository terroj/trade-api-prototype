<?php

namespace Terroj\PayeerClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class PayeerResponse extends Response
{
    /**
     * Contains a response result as array.
     * 
     * @var array|null
     */
    private array|null $arrayResponse = null;

    /**
     * Gets the status for payeer bad request.
     */
    protected const BAD_REQUEST_STATUS_CODE = 400;

    /**
     * Gets the status for payeer unknown error.
     */
    protected const UNKNOWN_ERROR_STATUS_CODE = 500;

    /**
     * List of payeer codes indicating a bad request.
     */
    protected const BAD_REQUEST_CODES = [
        'INVALID_SIGNATURE',
        'INVALID_IP_ADDRESS',
        'LIMIT_EXCEEDED',
        'INVALID_TIMESTAMP',
        'ACCESS_DENIED',
        'INVALID_PARAMETER',
        'PARAMETER_EMPTY',
        'INVALID_STATUS_FOR_REFUND',
        'REFUND_LIMIT',
        'INVALID_DATE_RANGE',
        'INSUFFICIENT_FUNDS',
        'INSUFFICIENT_VOLUME',
        'INCORRECT_PRICE',
        'MIN_AMOUNT',
        'MIN_VALUE',
    ];

    /**
     * Creates a PayeerResponse.
     *
     * @param ResponseInterface $response An instance of base response from guzzle.
     */
    public function __construct(ResponseInterface $response)
    {
        $body = $response->getBody();
        $headers = $response->getHeaders();
        $this->arrayResponse = $this->convertResponseToArray($response);

        $status = $this->parseStatusCode($response, $this->arrayResponse);

        parent::__construct($status, $headers, $body);
    }

    /**
     * Returns error details from the Payeer.
     *
     * @return array
     */
    public function getError(): array
    {
        if ($this->isError()) {
            return $this->getArrayValue($this->arrayResponse, 'error', []);
        }

        return [];
    }

    /**
     * Gets the Payeer error code.
     *
     * @param array $content
     * @return string|null
     */
    public function getErrorCode(): string|null
    {
        return $this->getPayeerErrorCode($this->getArrayResponse());
    }

    /**
     * Determines if the request failed.
     *
     * @return boolean
     */
    public function isError(): bool
    {
        return $this->getStatusCode() >= 400;
    }

    /**
     * Determines if the request was completed successfully.
     *
     * @return boolean
     */
    public function isSuccess(): bool
    {
        return !$this->IsError();
    }

    /**
     * Returns the response body as an array.
     *
     * @return array
     */
    public function getArrayResponse(): array
    {
        return $this->arrayResponse;
    }

    /**
     * Convert the Payeer response to a psr-7 status code.
     *
     * @param ResponseInterface $response
     * @param array $content
     * @return integer Status code.
     */
    protected function parseStatusCode(ResponseInterface $response, array $content): int
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            return $statusCode;
        }

        if ($this->getArrayValue($content, 'success') === true) {
            return $statusCode;
        }

        $errorCode = $this->getPayeerErrorCode($content);
        $isBadRequest = in_array($errorCode, static::BAD_REQUEST_CODES);

        return $isBadRequest
            ? static::BAD_REQUEST_STATUS_CODE
            : static::UNKNOWN_ERROR_STATUS_CODE;
    }

    /**
     * Converts response body to an array.
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function convertResponseToArray(ResponseInterface $response): array
    {
        $body = $response->getBody();
        $content = $body->getContents();

        // Rewinds to the beginning of the stream, for those who want again to read the stream.
        $body->rewind();

        return json_decode($content, true) ?? [];
    }

    /**
     * Gets the Payeer error code.
     *
     * @param array $content
     * @return string|null
     */
    private function getPayeerErrorCode(array $content): string|null
    {
        $error = $this->getArrayValue($content, 'error');
        $errorCode = $this->getArrayValue($error, 'code');

        return $errorCode;
    }

    /**
     * Gets a value from an array by key, if the value does not exist, returns the default value.
     * 
     * If the key is not provided, returns the given array.
     * 
     * @param array|null $array An array.
     * @param string $key An array key.
     * @param mixed $default Default value
     * @return mixed|null
     */
    private function getArrayValue(array|null $array, string $key, mixed $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        return $array[$key] ?? $default;
    }
}
