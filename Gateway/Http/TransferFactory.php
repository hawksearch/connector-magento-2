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

use HawkSearch\Connector\Gateway\Request\BuilderInterface;
use HawkSearch\Connector\Model\ConfigProvider;

class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var TransferBuilder
     */
    private $transferBuilder;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var BuilderInterface
     */
    private $headers;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $method;

    /**
     * @param TransferBuilder $transferBuilder
     * @param ConfigProvider $configProvider
     * @param BuilderInterface $headers
     * @param string $uri
     * @param string $method
     */
    public function __construct(
        TransferBuilder $transferBuilder,
        ConfigProvider $configProvider,
        BuilderInterface $headers,
        $uri,
        $method
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->configProvider = $configProvider;
        $this->headers = $headers;
        $this->uri = $uri;
        $this->method = $method;
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
            ->setUri($this->configProvider->getApiUrl() . $this->uri)
            ->setHeaders($this->headers->build([]))
            ->setBody($request)
            ->build();
    }
}
