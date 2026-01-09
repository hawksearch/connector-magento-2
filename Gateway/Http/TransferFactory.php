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
    private TransferBuilder $transferBuilder;
    private ApiConfigInterface $apiConfig;
    private ConnectionScopeResolver $connectionScopeResolver;
    private string $path;
    private string $method;
    private BuilderInterface $headersBuilder;
    private UriBuilderInterface $uriBuilder;

    public function __construct(
        TransferBuilder $transferBuilder,
        ApiConfigInterface $apiConfig,
        RequestInterface $httpRequest, // @todo remove $httpRequest argument
        UriBuilderFactory $uriBuilderFactory,
        BuilderInterfaceFactory $builderInterfaceFactory,
        ConnectionScopeResolver $connectionScopeResolver,
        string $path = '',
        string $method = 'GET'
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->apiConfig = $apiConfig;
        $this->connectionScopeResolver = $connectionScopeResolver;
        $this->path = $path;
        $this->method = $method;
        $this->headersBuilder = $builderInterfaceFactory->create();
        $this->uriBuilder = $uriBuilderFactory->create();
    }

    /**
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
     */
    private function buildFullApiUrl(): string
    {
        return $this->uriBuilder->build(
            $this->apiConfig->getApiUrl($this->connectionScopeResolver->resolve()->getId()),
            $this->path
        );
    }

}
