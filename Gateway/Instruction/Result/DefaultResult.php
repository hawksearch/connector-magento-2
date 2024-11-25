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

use HawkSearch\Connector\Gateway\Instruction\ResultInterface;

/**
 * @api
 * @since 2.11
 *
 * @phpstan-import-type HttpResult from ResultInterface
 */
class DefaultResult implements ResultInterface
{
    /**
     * @var HttpResult
     */
    private $result;

    /**
     * @param HttpResult $result
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * @inheriDoc
     */
    public function get()
    {
        return $this->result;
    }
}
