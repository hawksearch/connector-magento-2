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
    const XML_ROOT_PATH = 'hawksearch_connector';
    const XML_GENERAL_GROUP = 'general';

    private ScopeConfigInterface $scopeConfig;
    private string $configRootPath;
    private string $configGroup;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ?string $configRootPath = null,
        ?string $configGroup = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configRootPath = $configRootPath ?? static::XML_ROOT_PATH;
        $this->configGroup = $configGroup ?? static::XML_GENERAL_GROUP;
    }

    /**
     * Get absolute path for $path parameter
     *
     * @return string
     */
    public function getPath(string $path)
    {
        return $this->configRootPath . '/' . $this->configGroup . '/' . $path;
    }

    public function getConfig(string $path, $scopeId = null, string $scope = ScopeInterface::SCOPE_STORES)
    {
        return $this->scopeConfig->getValue(
            $this->getPath($path),
            $scope,
            $scopeId
        );
    }
}
