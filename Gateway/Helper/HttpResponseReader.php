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

namespace HawkSearch\Connector\Gateway\Helper;

use HawkSearch\Connector\Gateway\Http\ClientInterface;

/**
 * @api
 * @since 2.11
 *
 * @todo get rid of this class in favour of HttpResultInterface
 * @see ClientInterface
 *
 * @phpstan-import-type HttpResult from ClientInterface
 */
class HttpResponseReader
{
    /**
     * @param HttpResult $subject
     * @return string
     */
    public function readResponseCode(array $subject)
    {
        if (!isset($subject[ClientInterface::RESPONSE_CODE])) {
            throw new \InvalidArgumentException('Response code does not exist');
        }

        // @phpstan-ignore-next-line return type
        return $subject[ClientInterface::RESPONSE_CODE];
    }

    /**
     * @param HttpResult $subject
     * @return mixed
     */
    public function readResponseData(array $subject)
    {
        if (!isset($subject[ClientInterface::RESPONSE_DATA])) {
            throw new \InvalidArgumentException('Response data does not exist');
        }

        return $subject[ClientInterface::RESPONSE_DATA];
    }

    /**
     * @param HttpResult $subject
     * @return string
     */
    public function readResponseMessage(array $subject)
    {
        if (!isset($subject[ClientInterface::RESPONSE_MESSAGE])
            || !is_string($subject[ClientInterface::RESPONSE_MESSAGE])
        ) {
            throw new \InvalidArgumentException('Response message does not exist');
        }

        return $subject[ClientInterface::RESPONSE_MESSAGE];
    }
}
