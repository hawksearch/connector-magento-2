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
use HawkSearch\Connector\Gateway\Validator\ValidatorInterface;

/**
 * @api
 * @since 2.11
 *
 * @phpstan-import-type HttpResult from ClientInterface
 * @phpstan-import-type ValidationSubject from ValidatorInterface
 */
class SubjectReader
{
    /**
     * @param ValidationSubject $subject
     * @return HttpResult
     * @todo get rid of this method in favour of HttpResultInterface
     * @see ClientInterface
     */
    public function readResponse(array $subject)
    {
        if (!isset($subject['response'])) {
            throw new \InvalidArgumentException('Invalid response');
        }

        return $subject['response'];
    }
}
