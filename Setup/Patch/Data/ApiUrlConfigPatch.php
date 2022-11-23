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

namespace HawkSearch\Connector\Setup\Patch\Data;

use Magento\Config\Model\ResourceModel\Config as ConfigResource;
use Magento\Framework\DB\Adapter\DuplicateException;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ApiUrlConfigPatch implements DataPatchInterface
{
    /**
     * @var string[]
     * @SuppressWarnings(Generic.Files.LineLength.TooLong)
     */
    // phpcs:disable Generic.Files.LineLength.TooLong
    protected $directivesToRename = [
        'hawksearch_connector/api_settings/order_tracking_key' => 'hawksearch_connector/api_settings/client_guid',
        'hawksearch_connector/api_settings/mode' => 'hawksearch_connector/api_settings/environment',
        'hawksearch_connector/api_settings/rec_url/develop' => 'hawksearch_connector/api_settings/recommendations_url/develop',
        'hawksearch_connector/api_settings/rec_url/staging' => 'hawksearch_connector/api_settings/recommendations_url/staging',
        'hawksearch_connector/api_settings/rec_url/production' => 'hawksearch_connector/api_settings/recommendations_url/production',
    ];
    // phpcs:enable

    /**
     * @var ConfigResource
     */
    private $config;

    /**
     * @param ConfigResource $config
     */
    public function __construct(
        ConfigResource $config
    ) {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        foreach ($this->directivesToRename as $pathFrom => $pathTo) {
            try {
                $bind = ['path' => $pathTo];
                $where = ['path = ?' => $pathFrom];
                $this->config->getConnection()->update($this->config->getMainTable(), $bind, $where);
            } catch (DuplicateException $e) {
                // Skip
            }
        }
    }
}
