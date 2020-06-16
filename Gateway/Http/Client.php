<?php
/**
 *  Copyright (c) 2020 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 *  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 *  IN THE SOFTWARE.
 */
declare(strict_types=1);

namespace HawkSearch\Connector\Gateway\Http;

use InvalidArgumentException;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface $logger
     * @param ZendClientFactory $httpClientFactory
     * @param Json $json
     */
    public function __construct(
        LoggerInterface $logger,
        ZendClientFactory $httpClientFactory,
        Json $json
    ) {
        $this->httpClientFactory = $httpClientFactory;
        $this->logger = $logger;
        $this->json = $json;
    }

    /**
     * @param TransferInterface $transferObject
     * @return array
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $request = $transferObject->getBody();
        //TODO implement connector logger
//        $log = [
//            'request' => $request,
//            'request_uri' => $transferObject->getUri()
//        ];

        $responseData = [
            self::RESPONSE_CODE => 0,
            self::RESPONSE_MESSAGE => 'API request wasn\'t processed.' ,
            self::RESPONSE_DATA => ''
        ];

        $client = $this->httpClientFactory->create();

        try {
            $client->setUri($transferObject->getUri());
            $client->setMethod($transferObject->getMethod());
            if ($transferObject->getHeaders()) {
                $client->setHeaders($transferObject->getHeaders());
            }
            if ($request) {
                $client->setRawData($this->json->serialize($request), 'application/json');
            }

            $response = $client->request();

            $responseData[self::RESPONSE_CODE] = $response->getStatus();
            $responseData[self::RESPONSE_MESSAGE] = $response->getMessage();
            $responseData[self::RESPONSE_DATA] = $this->json->unserialize($response->getBody());

        } catch (InvalidArgumentException $e) {
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $e->getMessage();
        } catch (\Zend_Http_Client_Exception $e) {
            $this->logger->critical($e);
            $responseData[self::RESPONSE_MESSAGE] = $e->getMessage();
        }
        //TODO implement connector logger
//        finally {
//            $this->hawkLogger->debug($log);
//        }
        return $responseData;
    }
}
