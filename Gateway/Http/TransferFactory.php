<?php
/**
 * Copyright (c) 2023 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

use HawkSearch\Connector\Gateway\Config\ApiConfigInterface;
use HawkSearch\Connector\Gateway\Http\Uri\UriBuilderFactory;
use HawkSearch\Connector\Gateway\Http\Uri\UriBuilderInterface;
use HawkSearch\Connector\Gateway\Request\BuilderInterface;
use HawkSearch\Connector\Gateway\Request\BuilderInterfaceFactory;
use HawkSearch\Connector\Model\ConnectionScopeResolver;
use Laminas\Http\Client\Adapter\Curl;
use Magento\Framework\App\RequestInterface;

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
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;

    /**
     * @var BuilderInterface
     */
    private $headersBuilder;

    /**
     * @var UriBuilderInterface
     */
    private $uriBuilder;

    /**
     * @var RequestInterface
     */
    private $httpRequest;

    /**
     * @var ConnectionScopeResolver
     */
    private ConnectionScopeResolver $connectionScopeResolver;

    /**
     * @param TransferBuilder $transferBuilder
     * @param ApiConfigInterface $apiConfig
     * @param RequestInterface $httpRequest
     * @param UriBuilderFactory $uriBuilderFactory
     * @param string $path
     * @param string $method
     * @param BuilderInterface|null $headersBuilder
     * @param UriBuilderInterface|null $uriBuilder
     */
    public function __construct(
        TransferBuilder $transferBuilder,
        ApiConfigInterface $apiConfig,
        RequestInterface $httpRequest,
        UriBuilderFactory $uriBuilderFactory,
        BuilderInterfaceFactory $builderInterfaceFactory,
        ConnectionScopeResolver $connectionScopeResolver,
        $path = '',
        $method = 'GET',
        BuilderInterface $headersBuilder = null,
        UriBuilderInterface $uriBuilder = null
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->apiConfig = $apiConfig;
        $this->httpRequest = $httpRequest;
        $this->path = $path;
        $this->method = $method;
        $this->headersBuilder = $headersBuilder ?? $builderInterfaceFactory->create();
        $this->uriBuilder = $uriBuilder ?? $uriBuilderFactory->create();
        $this->connectionScopeResolver = $connectionScopeResolver;
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
            ->setHeaders($this->headersBuilder->build([]))
            ->setBody($request)
            ->setClientConfig([
                'adapter' => Curl::class,
                'timeout' => 45
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
        return $this->uriBuilder->build(
            $this->apiConfig->getApiUrl($this->connectionScopeResolver->resolve()->getId()),
            $this->path
        );
    }
}
