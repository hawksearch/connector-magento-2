<?php
/**
 * Copyright (c) 2026 Hawksearch (www.hawksearch.com) - All Rights Reserved
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
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class RemoteAddressResolver implements RemoteAddressResolverInterface
{
    private RequestInterface $request;
    private RemoteAddress $remoteAddress;

    public function __construct(
        RequestInterface $request,
        RemoteAddress $remoteAddress
    ) {
        $this->request = $request;
        $this->remoteAddress = $remoteAddress;
    }

    private function getForwardedIp(): ?string
    {
        return $this->request->getServer('HTTP_X_FORWARDED_FOR');
    }

    public function resolve(): ?string
    {
        return $this->getForwardedIp() ?: $this->remoteAddress->getRemoteAddress();
    }
}
