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

use Magento\Store\Model\ScopeInterface;

/**
 * @api
 * @since 2.11
 */
interface ConfigProviderInterface
{
    /**
     * Get config value
     * @param string $path
     * @param int|string|null $scopeId Scope id | Scope code | null for default scope id
     * @param string $scope
     * @return mixed
     * @todo chnage $scopeId type to int
     */
    public function getConfig(string $path, $scopeId = null, string $scope = ScopeInterface::SCOPE_STORES);
}
