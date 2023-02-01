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

namespace HawkSearch\Connector\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class ConnectionScopeResolver
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ConfigurationStoreViewResolver constructor.
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterface $request,
        StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * Resolve store depending on Application area
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function resolve()
    {
        $storeId = $this->isBackendSystemConfigController()
            ? $this->request->getParam('store', Store::DEFAULT_STORE_ID)
            : null;

        return $this->storeManager->getStore($storeId);
    }

    /**
     * Get controller key
     *
     * @return string
     */
    protected function getControllerKey()
    {
        return $this->request->getRouteName() . '_' . $this->request->getControllerName();
    }

    /**
     * Check if current controller is in System Configuration
     *
     * @return bool
     */
    protected function isBackendSystemConfigController()
    {
        return $this->getControllerKey() === 'adminhtml_system_config';
    }
}
