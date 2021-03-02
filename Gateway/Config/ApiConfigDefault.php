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

namespace HawkSearch\Connector\Gateway\Config;

use HawkSearch\Connector\Model\Config\ApiSettings as ApiSettingsProvider;

class ApiConfigDefault implements ApiConfigInterface
{
    /**
     * @var ApiSettingsProvider
     */
    private $apiSettingsProvider;

    /**
     * ApiConfigDefault constructor.
     * @param ApiSettingsProvider $apiSettingsProvider
     */
    public function __construct(
        ApiSettingsProvider $apiSettingsProvider
    ) {
        $this->apiSettingsProvider = $apiSettingsProvider;
    }

    /**
     * @inheritDoc
     */
    public function getApiUrl(): ?string
    {
        return $this->apiSettingsProvider->getApiUrl();
    }

    /**
     * @inheritDoc
     */
    public function getApiKey(): ?string
    {
        return $this->apiSettingsProvider->getApiKey();
    }
}
