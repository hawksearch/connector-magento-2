<?php
/**
 * Copyright (c) 2021 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

use HawkSearch\Connector\Gateway\Logger\LoggerFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
use Magento\Framework\HTTP\ZendClient as HttpClient;

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
     * @var \HawkSearch\Connector\Gateway\Logger\LogInterface
     */
    private $gatewayLogger;

    /**
     * @param LoggerInterface $logger
     * @param ZendClientFactory $httpClientFactory
     * @param Json $json
     * @param ConverterInterface $converter
     * @param LoggerFactory $loggerFactory
     * @throws NoSuchEntityException
     */
    public function __construct(
        LoggerInterface $logger,
        ZendClientFactory $httpClientFactory,
        Json $json,
        ConverterInterface $converter,
        LoggerFactory $loggerFactory
    ) {
        $this->httpClientFactory = $httpClientFactory;
        $this->logger = $logger;
        $this->json = $json;
        $this->converter = $converter;
        $this->gatewayLogger = $loggerFactory->create();
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

        $log = [
            'request' => [
                'uri' => $transferObject->getUri(),
                'body' => $requestBody,
                'method' => $transferObject->getMethod(),
            ],
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

            $clientConfig = $transferObject->getClientConfig();

            if ($transferObject->getMethod() === HttpClient::GET) {
                $client->setParameterGet($requestBody);
            } else {
                $client->setRawData(!empty($requestBody) ? $this->json->serialize($requestBody) : '');
                $client->setHeaders(HttpClient::CONTENT_TYPE, 'application/json');

                /**
                 * Fix support of PATCH request for \Magento\Framework\HTTP\Adapter\Curl
                 */
                $clientConfig[CURLOPT_CUSTOMREQUEST] = $transferObject->getMethod();
                if ($transferObject->getMethod() === HttpClient::PATCH) {
                    $clientConfig[CURLOPT_POSTFIELDS] = $this->json->serialize($requestBody);
                }
            }
            $client->setConfig($clientConfig);

            if ($client->getAdapter() instanceof Curl) {
                $client->getAdapter()->setOptions($this->filterAdapterOptions(
                    $clientConfig
                ));
            }

            $response = $client->request();
            $responseBody = $response->getBody();
            $log['response'] = [
                'body' => $responseBody,
                'status' => $response->getStatus() . ' ' . $response->getMessage(),
            ];

            $responseData[self::RESPONSE_DATA] = $this->converter->convert($responseBody);
            $responseData[self::RESPONSE_CODE] = $response->getStatus();
            $responseData[self::RESPONSE_MESSAGE] = $response->getMessage();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $log['error'] = $e->getMessage();
            $responseData[self::RESPONSE_MESSAGE] = $e->getMessage();
        }
        finally {
            $this->gatewayLogger->debug($log);
        }
        return $responseData;
    }

    /**
     * @param array $options
     * @return array
     */
    private function filterAdapterOptions($options)
    {
        unset($options['adapter']);
        return $options;
    }
}
