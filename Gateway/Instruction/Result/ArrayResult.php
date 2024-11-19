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

namespace HawkSearch\Connector\Gateway\Instruction\Result;

use HawkSearch\Connector\Gateway\Helper\HttpResponseReader;
use HawkSearch\Connector\Gateway\Instruction\ResultInterface;

/**
 * @api
 * @since 2.11
 */
class ArrayResult implements ResultInterface
{
    /**
     * @var array
     */
    private $result;

    /**
     * @var HttpResponseReader
     */
    private $httpResponseReader;

    /**
     * @param HttpResponseReader $httpResponseReader
     * @param array $result
     */
    public function __construct(
        HttpResponseReader $httpResponseReader,
        array $result = []
    )
    {
        $this->result = $result;
        $this->httpResponseReader = $httpResponseReader;
    }

    /**
     * Returns result interpretation
     *
     * @return array
     */
    public function get()
    {
        $responseData = $this->httpResponseReader->readResponseData($this->result);
        return (array)$responseData;
    }
}
