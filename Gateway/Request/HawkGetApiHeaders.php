<?php
/**
 *  Copyright (c) 2020 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 *  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 *  IN THE SOFTWARE.
 */
declare(strict_types=1);

namespace HawkSearch\Connector\Gateway\Request;

use HawkSearch\Connector\Gateway\Config\ApiConfigInterface;

class HawkGetApiHeaders implements BuilderInterface
{
    /**
     * @var ApiConfigInterface
     */
    private $apiConfig;

    /**
     * HawkGetHeaders constructor.
     * @param ApiConfigInterface $apiConfig
     */
    public function __construct(
        ApiConfigInterface $apiConfig
    ) {
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        return [
            'X-HawkSearch-ApiKey' => $this->apiConfig->getApiKey()
        ];
    }
}
