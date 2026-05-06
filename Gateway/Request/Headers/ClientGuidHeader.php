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

use HawkSearch\Connector\Model\Config\ApiSettings;
use HawkSearch\Connector\Model\ConnectionScopeResolver;
use HawkSearch\Connector\Gateway\Request\BuilderInterface;

class ClientGuidHeader implements BuilderInterface
{
    private ApiSettings $apiSettings;
    private ConnectionScopeResolver $connectionScopeResolver;

    public function __construct(
        ApiSettings $apiSettings,
        ConnectionScopeResolver $connectionScopeResolver
    ) {
        $this->apiSettings = $apiSettings;
        $this->connectionScopeResolver = $connectionScopeResolver;
    }

    /**
     * @return array<mixed>
     */
    public function build(array $buildSubject)
    {
        return [
            'X-HawkSearch-ClientGuid' => $this->apiSettings->getClientGuid($this->connectionScopeResolver->resolve()->getId())
        ];
    }
}
