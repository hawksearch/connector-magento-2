<?php
/**
 * Copyright (c) 2022 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */
declare(strict_types=1);

namespace HawkSearch\Connector\Gateway\Http;

use HawkSearch\Connector\Logger\LoggerFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
use Zend_Http_Client;

class Client implements ClientInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ZendClientFactory
     */
    private $httpClientFactory;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @param ZendClientFactory $httpClientFactory
     * @param Json $json
     * @param ConverterInterface $converter
     * @param LoggerFactory $loggerFactory
     * @throws NoSuchEntityException
     */
    public function __construct(
        ZendClientFactory $httpClientFactory,
        Json $json,
        ConverterInterface $converter,
        LoggerFactory $loggerFactory
    ) {
        $this->httpClientFactory = $httpClientFactory;
        $this->json = $json;
        $this->converter = $converter;
        $this->logger = $loggerFactory->create();
    }

    /**
     * @param TransferInterface $transferObject
     * @return array
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $requestBody = $transferObject->getBody();
        $responseData = [
            self::RESPONSE_CODE => 0,
            self::RESPONSE_MESSAGE => 'API request wasn\'t processed.' ,
            self::RESPONSE_DATA => ''
        ];

        $client = $this->httpClientFactory->create([
            'uri' => $transferObject->getUri(),
            'config' => $transferObject->getClientConfig()
        ]);

        try {
            $client->setMethod($transferObject->getMethod());

            if ($transferObject->getAuthUsername()) {
                $client->setAuth(
                    $transferObject->getAuthUsername(),
                    $transferObject->getAuthPassword()
                );
            }

            if ($transferObject->getHeaders()) {
                $client->setHeaders($transferObject->getHeaders());
            }

            $clientOptions = [];
            if ($transferObject->getMethod() === Zend_Http_Client::GET) {
                $client->setParameterGet($requestBody);
            } else {
                $requestBody = !empty($requestBody) ? $this->json->serialize($requestBody) : '';
                $client->setRawData($requestBody);
                $client->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/json');

                /**
                 * Fix support of PATCH and DELETE requests for @see \Magento\Framework\HTTP\Adapter\Curl
                 */
                $clientOptions[CURLOPT_CUSTOMREQUEST] = $transferObject->getMethod();
                if ($transferObject->getMethod() === Zend_Http_Client::PATCH
                    || $transferObject->getMethod() === Zend_Http_Client::DELETE
                ) {
                    $clientOptions[CURLOPT_POSTFIELDS] = $this->json->serialize($requestBody);
                }
            }
            $client->setConfig($transferObject->getClientConfig());
            if ($client->getAdapter() instanceof Curl) {
                $client->getAdapter()->setOptions($clientOptions);
            }

            $response = $client->request();

            $responseData[self::RESPONSE_DATA] = $this->converter->convert($response->getBody());
            $responseData[self::RESPONSE_CODE] = $response->getStatus();
            $responseData[self::RESPONSE_MESSAGE] = $response->getMessage();
        } catch (\Zend_Http_Client_Exception $e) {
            $message = $e->getMessage();
            if ($e->getCode()) {
                $message .= '; Adapter: ' . get_class($client->getAdapter()) . '; Error Code: ' . $e->getCode();
            }
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $message;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $e->getMessage();
        } finally {
            $this->logger->info(
                'Api Client Request:',
                array(
                    'method'    => $transferObject->getMethod(),
                    'uri'       => $transferObject->getUri(),
                    'headers'   => $transferObject->getHeaders(),
                )
            );
            $this->logger->debug('Request Body:', [$requestBody]);

            $this->logger->info(
                'Api Client Response:',
                array(
                    'status'    => $responseData[self::RESPONSE_CODE],
                    'message'    => $responseData[self::RESPONSE_MESSAGE],
                )
            );
            $this->logger->debug('Response Body:', $responseData[self::RESPONSE_DATA]);
        }

        return $responseData;
    }
}
