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

namespace HawkSearch\Connector\Gateway\Validator;

/**
 * Interface ValidatorInterface
 * @api
 *
 * @phpstan-type ValidationSubject array<string, mixed>
 */
interface ValidatorInterface
{
    /**
     * Performs domain-related validation for business object
     *
     * @param ValidationSubject $validationSubject
     * @return ResultInterface
     * @todo change signature to validate(\HawkSearch\Connector\Gateway\Http\TransferInterface $transfer, HttpResultInterface $result)
     * @see \HawkSearch\Connector\Gateway\Instruction\ResultInterface
     * @see \HawkSearch\Connector\Gateway\Http\TransferInterface
     */
    public function validate(array $validationSubject);
}
