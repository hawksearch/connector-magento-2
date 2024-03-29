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

namespace HawkSearch\Connector\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigProvider
 * System config provider
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * XML configuration root paths
     */
    const XML_ROOT_PATH = 'hawksearch_connector';

    /**
     * XML configuration general group
     */
    const XML_GENERAL_GROUP = 'general';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string
     */
    private $configGroup;

    /**
     * @var string
     */
    private $configRootPath;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param string|null $configRootPath
     * @param string|null $configGroup
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $configRootPath = null,
        $configGroup = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configRootPath = $configRootPath ?? static::XML_ROOT_PATH;
        $this->configGroup = $configGroup ?? static::XML_GENERAL_GROUP;
    }

    /**
     * Get absolute path for $path parameter
     *
     * @param string $path Absolute path
     * @return string
     */
    public function getPath($path)
    {
        return $this->configRootPath . '/' . $this->configGroup . '/' . $path;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(string $path, $scopeId = null, $scope = ScopeInterface::SCOPE_STORES)
    {
        return $this->scopeConfig->getValue(
            $this->getPath($path),
            $scope,
            $scopeId
        );
    }
}
