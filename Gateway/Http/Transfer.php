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

    /**
     * @var array
     */
    private $clientConfig;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array|string
     */
    private $body;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var bool
     */
    private $encode;

    /**
     * @var array
     */
    private $auth;

    /**
     * @param array $clientConfig
     * @param array $headers
     * @param array|string $body
     * @param array $auth
     * @param string $method
     * @param string $uri
     * @param bool $encode
     */
    public function __construct(
        array $clientConfig,
        array $headers,
        $body,
        array $auth,
        $method,
        $uri,
        $encode
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
        return (string)$this->method;
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

    /**
     * Returns request body
     *
     * @return array|string
     */
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
        return (string)$this->uri;
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
