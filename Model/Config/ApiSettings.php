<?php
/**
 * Copyright (c) 2022 Hawksearch (www.hawksearch.com) - All Rights Reserved
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
    public const API_KEY = 'api_key';
    public const ENGINE_NAME = 'engine_name';
    public const CLIENT_GUID = 'client_guid';
    public const ENVIRONMENT = 'environment';
    public const HAWK_URL = 'hawk_url';
    public const TRACKING_URL = 'tracking_url';
    public const RECOMMENDATIONS_URL = 'recommendations_url';
    public const DASHBOARD_API_URL = 'dashboard_api_url';
    public const HAWKSEARCH_WORKBENCH_URL = 'hawksearch_workbench_url';
    public const INDEXING_API_URL = 'indexing_api_url';
    public const SEARCH_API_URL = 'search_api_url';
    /**#@-*/

    /**
     * Get Api key
     *
     * @param null|int|string $store
     * @return string
     */
    public function getApiKey($store = null) : string
    {
        return (string)$this->getConfig(self::API_KEY, $store);
    }

    /**
     * Get Engine Name
     *
     * @param null|int|string $store
     * @return string
     */
    public function getEngineName($store = null) : string
    {
        return (string)$this->getConfig(self::ENGINE_NAME, $store);
    }

    /**
     * Get Hawksearch Environment
     *
     * @param null|int|string $store
     * @return string
     */
    public function getEnvironment($store = null) : string
    {
        return (string)$this->getConfig(self::ENVIRONMENT, $store);
    }

    /**
     * @deprecated 2.5.1
     * @see \HawkSearch\Connector\Model\Config\ApiSettings::getClientGuid
     */
    public function getOrderTrackingKey($store = null) : string
    {
        return $this->getClientGuid($store);
    }

    /**
     * @deprecated 2.6.0
     * @see \HawkSearch\Connector\Model\Config\ApiSettings::getClientGuid
     */
    public function getTrackingKey($store = null) : string
    {
        return $this->getClientGuid($store);
    }

    /**
     * Get Client Guid / Tracking Key
     *
     * @param null|int|string $store
     * @return string
     */
    public function getClientGuid($store = null) : string
    {
        return (string)$this->getConfig(self::CLIENT_GUID, $store);
    }

    /**
     * Get Hawksearch Lucene Engine Reference URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getHawkUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::HAWK_URL, $store);
    }

    /**
     * Get Hawksearch Tracking URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getTrackingUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::TRACKING_URL, $store);
    }

    /**
     * Get Hawksearch Recommendations URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getRecommendationsUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::RECOMMENDATIONS_URL, $store);
    }

    /**
     * Get Hawksearch Dashboard API URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getDashboardApiUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::DASHBOARD_API_URL, $store);
    }

    /**
     * Get Hawksearch Workbench URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getHawksearchWorkbenchUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::HAWKSEARCH_WORKBENCH_URL, $store);
    }

    /**
     * Get Hawksearch Indexing API URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getIndexingApiUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::INDEXING_API_URL, $store);
    }

    /**
     * Get Hawksearch Search API URL
     *
     * @param null|int|string $store
     * @return string
     */
    public function getSearchApiUrl($store = null) : string
    {
        return $this->getEnvironmentConfig(self::SEARCH_API_URL, $store);
    }

    /**
     * Get config depending on selected Hawksearch environment
     *
     * @param string $path Config path part
     * @param null|int|string $store
     * @return string
     */
    private function getEnvironmentConfig(string $path, $store = null) : string
    {
        return (string)$this->getConfig($path . '/' . $this->getEnvironment(), $store);
    }
}
