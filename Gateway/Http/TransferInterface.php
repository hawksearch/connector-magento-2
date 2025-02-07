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
interface TransferInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getClientConfig();

    /**
     * Returns HTTP method used to place request
     *
     * @return string
     */
    public function getMethod();

    /**
     * @return array<array-key, mixed>
     */
    public function getHeaders();

    /**
     * Whether body should be encoded before place
     *
     * @return bool
     */
    public function shouldEncode();

    /**
     * @return RequestSubject
     */
    public function getBody();

    /**
     * @return string
     */
    public function getUri();

    /**
     * @return string
     */
    public function getAuthUsername();

    /**
     * @return string
     */
    public function getAuthPassword();
}
