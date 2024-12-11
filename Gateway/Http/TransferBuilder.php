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
 *
 * @phpstan-import-type RequestSubject from \HawkSearch\Connector\Gateway\InstructionInterface
 * @todo replace RequestSubject pseudo type by RequestInterface
 */
class TransferBuilder
{
    /**
     * @var array<string, mixed>
     */
    private $clientConfig = [];

    /**
     * @var array<array-key, mixed>
     */
    private $headers = [];

    /**
     * @var string
     */
    private $method;

    /**
     * @var RequestSubject
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
     * @var array<Transfer::AUTH_*, string>
     */
    private $auth = [Transfer::AUTH_USERNAME => '', Transfer::AUTH_PASSWORD => ''];

    /**
     * @param array<string, mixed> $clientConfig
     * @return $this
     */
    public function setClientConfig(array $clientConfig)
    {
        $this->clientConfig = $clientConfig;

        return $this;
    }

    /**
     * @param array<array-key, mixed> $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param RequestSubject $body
     * @return $this
     */
    public function setBody(array $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return $this
     */
    public function setAuthUsername(string $username)
    {
        $this->auth[Transfer::AUTH_USERNAME] = $username;

        return $this;
    }

    /**
     * @return $this
     */
    public function setAuthPassword(string $password)
    {
        $this->auth[Transfer::AUTH_PASSWORD] = $password;

        return $this;
    }

    /**
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return $this
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return $this
     */
    public function shouldEncode(bool $encode)
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
