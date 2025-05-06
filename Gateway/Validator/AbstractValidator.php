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
 * Represents a basic validator that can create a result
 *
 * @todo Get rid of Abstract class in favour of builder for ResultInterface objects
 */
abstract class AbstractValidator implements ValidatorInterface
{
    private ResultInterfaceFactory $resultInterfaceFactory;

    public function __construct(
        ResultInterfaceFactory $resultFactory
    ) {
        $this->resultInterfaceFactory = $resultFactory;
    }

    /**
     * Factory method
     *
     * @param bool $isValid
     * @param list<string|\Stringable> $fails
     * @param list<int> $errorCodes
     * @return ResultInterface
     */
    protected function createResult(bool $isValid, array $fails = [], array $errorCodes = [])
    {
        return $this->resultInterfaceFactory->create(
            [
                'isValid' => $isValid,
                'failsDescription' => $fails,
                'errorCodes' => $errorCodes
            ]
        );
    }
}
