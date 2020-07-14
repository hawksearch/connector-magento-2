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

use HawkSearch\Connector\Gateway\ApiConfigInterface;
use HawkSearch\Connector\Model\ConfigProvider;

class ApiConfigDefault implements ApiConfigInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * ApiConfigDefault constructor.
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {

        $this->configProvider = $configProvider;
    }

    /**
     * @inheritDoc
     */
    public function getApiUrl(): string
    {
        return $this->configProvider->getApiUrl();
    }

    /**
     * @inheritDoc
     */
    public function getApiKey(): string
    {
        return $this->configProvider->getApiKey();
    }
}
