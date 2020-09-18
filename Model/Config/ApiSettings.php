<?php
/**
 * Copyright (c) 2020 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Model\Config;

use HawkSearch\Connector\Model\ConfigProvider;

class ApiSettings extends ConfigProvider
{
    /**#@+
     * Configuration paths
     */
    const API_KEY = 'api_key';
    const ENGINE_NAME = 'engine_name';
    const API_MODE = 'mode';
    const ORDER_TRACKING_KEY = 'order_tracking_key';
    const API_URL = 'hawk_url';
    const TRACKING_URL = 'tracking_url';
    const REC_URL = 'rec_url';
    /**#@-*/

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getApiKey($store = null) : ?string
    {
        return $this->getConfig(self::API_KEY, $store);
    }

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getEngineName($store = null) : ?string
    {
        return $this->getConfig(self::ENGINE_NAME, $store);
    }

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getApiMode($store = null) : ?string
    {
        return $this->getConfig(self::API_MODE, $store);
    }

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getOrderTrackingKey($store = null) : ?string
    {
        return $this->getConfig(self::ORDER_TRACKING_KEY, $store);
    }

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getApiUrl($store = null) : ?string
    {
        return $this->getConfig(self::API_URL . '/' . $this->getApiMode(), $store);
    }

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getTrackingUrl($store = null) : ?string
    {
        return $this->getConfig(self::TRACKING_URL . '/' . $this->getApiMode(), $store);
    }

    /**
     * @param null|int|string $store
     * @return string | null
     */
    public function getRecommendationUrl($store = null) : ?string
    {
        return $this->getConfig(self::REC_URL . '/' . $this->getApiMode(), $store);
    }
}
