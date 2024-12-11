<?php
/**
 * Copyright (c) 2024 Hawksearch (www.hawksearch.com) - All Rights Reserved
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
use Laminas\Http\Client as LaminasClient;
use Laminas\Http\ClientFactory as LaminasClientFactory;
use Laminas\Http\Exception\RuntimeException;
use Laminas\Http\Request as HttpRequest;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

class Client implements ClientInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LaminasClientFactory
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

    public function __construct(
        LaminasClientFactory $httpClientFactory,
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
     * @return array
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $requestBody = $transferObject->getBody();
        $responseData = [
            self::RESPONSE_CODE => 0,
            self::RESPONSE_MESSAGE => 'API request wasn\'t processed.' ,
            self::RESPONSE_DATA => ''
        ];

        /** @var LaminasClient $client */
        $client = $this->httpClientFactory->create(
            [
                'uri' => $transferObject->getUri(),
                'options' => $transferObject->getClientConfig()
            ]
        );

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

            if ($transferObject->getMethod() === HttpRequest::METHOD_GET) {
                $client->setParameterGet($requestBody);
            } else {
                $requestBody = !empty($requestBody) ? $this->json->serialize($requestBody) : '';
                $client->setRawBody($requestBody);
                $client->setEncType('application/json');
            }

            $this->logger->info(
                'Api Client Request:',
                array(
                    'method'    => $client->getMethod(),
                    'uri'       => $client->getUri()->toString(),
                    'headers'   => $client->getRequest()->getHeaders()->toArray(),
                )
            );
            $this->logger->debug('Request Body:', (array)$requestBody);

            $response = $client->send();

            $responseData[self::RESPONSE_DATA] = $this->converter->convert($response->getBody());
            $responseData[self::RESPONSE_CODE] = $response->getStatusCode();
            $responseData[self::RESPONSE_MESSAGE] = $response->getReasonPhrase();

            $this->logger->info(
                'Api Client Response:',
                array(
                    'status'    => $responseData[self::RESPONSE_CODE],
                    'message'    => $responseData[self::RESPONSE_MESSAGE],
                )
            );
            $this->logger->debug('Response Body:', (array)$responseData[self::RESPONSE_DATA]);
        } catch (RuntimeException $e) {
            $message = $e->getMessage();
            if ($e->getCode()) {
                $message .= '; Adapter: ' . get_class($client->getAdapter()) . '; Error Code: ' . $e->getCode();
            }
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $message;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $e->getMessage();
        }

        return $responseData;
    }
}
