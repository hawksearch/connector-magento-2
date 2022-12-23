<?php
/**
 * Copyright (c) 2022 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

class HttpResponseReader
{
    /**
     * @param array $subject
     * @return string
     */
    public function readResponseCode(array $subject)
    {
        if (!isset($subject[ClientInterface::RESPONSE_CODE])) {
            throw new \InvalidArgumentException('Response code does not exist');
        }

        return $subject[ClientInterface::RESPONSE_CODE];
    }

    /**
     * @param array $subject
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
     * @param array $subject
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
