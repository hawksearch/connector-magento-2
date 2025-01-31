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

namespace HawkSearch\Connector\Gateway\Http;

/**
 * @phpstan-import-type RequestSubject from \HawkSearch\Connector\Gateway\InstructionInterface
 * @todo replace RequestSubject pseudo type by RequestInterface
 */
class Transfer implements TransferInterface
{
    /**
     * Name of Auth username field
     */
    const AUTH_USERNAME = 'username';
    /**
     * Name of Auth password field
     */
    const AUTH_PASSWORD = 'password';

    private array $clientConfig;
    private array $headers;
    private string $method;
    /**
     * @var RequestSubject
     */
    private array $body;
    private string $uri;
    private bool $encode;
    /**
     * @var array<self::AUTH_*, string>
     */
    private array $auth;
    
    /**
     * @param array<string, mixed> $clientConfig
     * @param array<array-key, mixed> $headers
     * @param RequestSubject $body
     * @param array<self::AUTH_*, string> $auth
     * @param string $method
     * @param string $uri
     * @param bool $encode
     */
    public function __construct(
        array $clientConfig,
        array $headers,
        array $body,
        array $auth,
        string $method,
        string $uri,
        bool $encode
    ) {
        $this->clientConfig = $clientConfig;
        $this->headers = $headers;
        $this->body = $body;
        $this->auth = $auth;
        $this->method = $method;
        $this->uri = $uri;
        $this->encode = $encode;
    }

    /**
     * Returns gateway client configuration
     *
     * @return array
     */
    public function getClientConfig()
    {
        return $this->clientConfig;
    }

    /**
     * Returns method used to place request
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    /**
     * Returns URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return boolean
     */
    public function shouldEncode()
    {
        return $this->encode;
    }

    /**
     * Returns Auth username
     *
     * @return string
     */
    public function getAuthUsername()
    {
        return $this->auth[self::AUTH_USERNAME];
    }

    /**
     * Returns Auth password
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->auth[self::AUTH_PASSWORD];
    }
}
