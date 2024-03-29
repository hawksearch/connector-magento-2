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

namespace HawkSearch\Connector\Gateway\Http;

/**
 * @api
 */
class TransferBuilder
{
    /**
     * @var array
     */
    private $clientConfig = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $method;

    /**
     * @var array|string
     */
    private $body = [];

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @var bool
     */
    private $encode = false;

    /**
     * @var array
     */
    private $auth = [Transfer::AUTH_USERNAME => null, Transfer::AUTH_PASSWORD => null];

    /**
     * @param array $clientConfig
     * @return $this
     */
    public function setClientConfig(array $clientConfig)
    {
        $this->clientConfig = $clientConfig;

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array|string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setAuthUsername($username)
    {
        $this->auth[Transfer::AUTH_USERNAME] = $username;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setAuthPassword($password)
    {
        $this->auth[Transfer::AUTH_PASSWORD] = $password;

        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @param bool $encode
     * @return $this
     */
    public function shouldEncode($encode)
    {
        $this->encode = $encode;

        return $this;
    }

    /**
     * @return TransferInterface
     */
    public function build()
    {
        return new Transfer(
            $this->clientConfig,
            $this->headers,
            $this->body,
            $this->auth,
            $this->method,
            $this->uri,
            $this->encode
        );
    }
}
