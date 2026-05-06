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

namespace HawkSearch\Connector\Test\Unit\Model;

use HawkSearch\Connector\Model\RemoteAddressResolver;
use Magento\Framework\App\Request\Http;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use PHPUnit\Framework\TestCase;

class RemoteAddressResolverTest extends TestCase
{
    public function testItPrefersForwardedIpWhenPresent(): void
    {
        $request = $this->createMock(Http::class);
        $request->method('getServer')->with('HTTP_X_FORWARDED_FOR')->willReturn('203.0.113.10, 10.0.0.1');

        $remoteAddress = $this->createMock(RemoteAddress::class);
        $remoteAddress->expects($this->never())->method('getRemoteAddress');

        $resolver = new RemoteAddressResolver($request, $remoteAddress);

        $this->assertSame('203.0.113.10, 10.0.0.1', $resolver->resolve());
    }

    public function testItFallsBackToRemoteAddressWhenForwardedHeaderIsMissing(): void
    {
        $request = $this->createMock(Http::class);
        $request->method('getServer')->with('HTTP_X_FORWARDED_FOR')->willReturn(null);

        $remoteAddress = $this->createMock(RemoteAddress::class);
        $remoteAddress->expects($this->once())->method('getRemoteAddress')->willReturn('198.51.100.42');

        $resolver = new RemoteAddressResolver($request, $remoteAddress);

        $this->assertSame('198.51.100.42', $resolver->resolve());
    }
}
