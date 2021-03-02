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

use InvalidArgumentException;
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
     * @param LoggerInterface $logger
     * @param ZendClientFactory $httpClientFactory
     * @param ConverterInterface $converter
     * @param Json $json
     */
    public function __construct(
        LoggerInterface $logger,
        ZendClientFactory $httpClientFactory,
        Json $json,
        ConverterInterface $converter
    ) {
        $this->httpClientFactory = $httpClientFactory;
        $this->logger = $logger;
        $this->json = $json;
        $this->converter = $converter;
    }

    /**
     * @param TransferInterface $transferObject
     * @return array
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $request = $transferObject->getBody();
        //TODO implement connector logger
        $log = [
            'request' => $request,
            'request_uri' => $transferObject->getUri()
        ];

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

            $clientConfig = $transferObject->getClientConfig();

            if ($transferObject->getMethod() === HttpClient::GET) {
                $client->setParameterGet($request);
            } else {
                $client->setRawData($this->json->serialize($request));
                $client->setHeaders(HttpClient::CONTENT_TYPE, 'application/json');

                /**
                 * Fix support of PATCH request for \Magento\Framework\HTTP\Adapter\Curl
                 */
                $clientConfig[CURLOPT_CUSTOMREQUEST] = $transferObject->getMethod();
                if ($transferObject->getMethod() === HttpClient::PATCH) {
                    $clientConfig[CURLOPT_POSTFIELDS] = $this->json->serialize($request);
                }
            }
            $client->setConfig($clientConfig);

            if ($client->getAdapter() instanceof Curl) {
                $client->getAdapter()->setOptions($this->filterAdapterOptions(
                    $clientConfig
                ));
            }

            $response = $client->request();

            $log['response'] = $response;

            $responseData[self::RESPONSE_CODE] = $response->getStatus();
            $responseData[self::RESPONSE_MESSAGE] = $response->getMessage();
            try {
                $responseData[self::RESPONSE_DATA] = $this->converter->convert($response->getBody());
            } catch (ConverterException $e) {
                throw new \Exception('Invalid JSON was returned by the gateway');
            }

        } catch (\Exception $e) {
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $e->getMessage();
        }
        //TODO implement connector logger
//        finally {
//            $this->hawkLogger->debug($log);
//        }
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
