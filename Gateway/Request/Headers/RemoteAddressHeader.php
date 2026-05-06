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

namespace HawkSearch\Connector\Gateway\Request\Headers;

use HawkSearch\Connector\Gateway\Request\BuilderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class RemoteAddressHeader implements BuilderInterface
{
    private RemoteAddress $remoteAddress;
    private RequestInterface $request;

    public function __construct(
        RemoteAddress $remoteAddress,
        RequestInterface $request
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->request = $request;
    }

    /**
     * @return array<mixed>
     */
    public function build(array $buildSubject)
    {
        // Prefer the raw X-Forwarded-For header to preserve the proxy chain
        // Fall back to RemoteAddress for direct connections
        $forwardedFor = $this->request->getServer('HTTP_X_FORWARDED_FOR')
            ?: $this->remoteAddress->getRemoteAddress();

        return [
            'X-Forwarded-For' => $forwardedFor ?: ''
        ];
    }
}
