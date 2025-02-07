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

namespace HawkSearch\Connector\Setup\Patch\Data;

use HawkSearch\Connector\Setup\Patch\SystemConfigPatcher;
use Magento\Framework\Exception\LocalizedException;
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
     * @var SystemConfigPatcher
     */
    private $patcher;

    public function __construct(
        SystemConfigPatcher $patcher
    ) {
        $this->patcher = $patcher;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    /**
     * @throws LocalizedException
     */
    public function apply()
    {
        $this->patcher->renamePath($this->directivesToRename);
        return $this;
    }
}
