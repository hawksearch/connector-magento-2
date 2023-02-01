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

namespace HawkSearch\Connector\Setup\Patch;

use Magento\Config\Model\ResourceModel\Config as ConfigResource;
use Magento\Framework\DB\Adapter\DuplicateException;
use Magento\Framework\Exception\LocalizedException;

class SystemConfigPatcher
{
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
     * Rename system configuration parameters paths. The path format is the following:
     *      'section/group1/.../groupN/field'
     * $directives = [
     *  'pathFrom' => 'pathTo',
     *  ...
     * ]
     *
     * @param array $directives
     * @return void
     * @throws LocalizedException
     */
    public function renamePath($directives)
    {
        foreach ($directives as $pathFrom => $pathTo) {
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
