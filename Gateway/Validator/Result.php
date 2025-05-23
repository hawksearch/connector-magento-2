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

class Result implements ResultInterface
{
    private bool $isValid;
    /**
     * @var list<string|\Stringable>
     */
    private array $failsDescription;
    /**
     * @var list<int>
     */
    private array $errorCodes;

    /**
     * @param bool $isValid
     * @param list<string|\Stringable> $failsDescription
     * @param list<int> $errorCodes
     */
    public function __construct(
        bool $isValid,
        array $failsDescription = [],
        array $errorCodes = []
    ) {
        $this->isValid = $isValid;
        $this->failsDescription = $failsDescription;
        $this->errorCodes = $errorCodes;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getFailsDescription(): array
    {
        return $this->failsDescription;
    }

    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }
}
