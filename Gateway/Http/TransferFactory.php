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

use HawkSearch\Connector\Gateway\ApiConfigInterface;
use HawkSearch\Connector\Gateway\Http\Uri\UriBuilderInterface;
use HawkSearch\Connector\Gateway\Request\BuilderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\Adapter\Curl;

class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var TransferBuilder
     */
    private $transferBuilder;

    /**
     * @var ApiConfigInterface
     */
    private $apiConfig;

    /**
     * @var BuilderInterface
     */
    private $headers;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;

    /**
     * @var UriBuilderInterface|null
     */
    private $uriBuilder;

    /**
     * @var RequestInterface
     */
    private $httpRequest;

    /**
     * @param TransferBuilder $transferBuilder
     * @param ApiConfigInterface $apiConfig
     * @param BuilderInterface $headers
     * @param RequestInterface $httpRequest
     * @param string $path
     * @param string $method
     * @param UriBuilderInterface|null $uriBuilder
     */
    public function __construct(
        TransferBuilder $transferBuilder,
        ApiConfigInterface $apiConfig,
        BuilderInterface $headers,
        RequestInterface $httpRequest,
        $path = '',
        $method = 'GET',
        UriBuilderInterface $uriBuilder = null
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->apiConfig = $apiConfig;
        $this->headers = $headers;
        $this->httpRequest = $httpRequest;
        $this->path = $path;
        $this->method = $method;
        $this->uriBuilder = $uriBuilder;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request)
    {
        return $this->transferBuilder
            ->setMethod($this->method)
            ->setUri($this->buildFullApiUrl())
            ->setHeaders($this->headers->build([]))
            ->setBody($request)
            ->setClientConfig([
                'adapter' => new Curl(),
                'useragent' => $this->httpRequest->getHeader('UserAgent'),
            ])
            ->build();
    }

    /**
     * Get Full URL based on relative URL.
     *
     * @return string
     */
    private function buildFullApiUrl()
    {
        if ($this->uriBuilder) {
            return $this->uriBuilder->build($this->apiConfig->getApiUrl(), $this->path);
        }
        return rtrim($this->apiConfig->getApiUrl(), '/') . '/' . ltrim($this->path, '/');
    }
}
