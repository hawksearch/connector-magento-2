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
namespace HawkSearch\Connector\Gateway\Validator;

use Magento\Framework\Phrase;

class Result implements ResultInterface
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var Phrase[]
     */
    private $failsDescription;

    /**
     * @var string[]
     */
    private $errorCodes;

    /**
     * @param bool $isValid
     * @param array $failsDescription
     * @param array $errorCodes
     */
    public function __construct(
        $isValid,
        array $failsDescription = [],
        array $errorCodes = []
    ) {
        $this->isValid = (bool)$isValid;
        $this->failsDescription = $failsDescription;
        $this->errorCodes = $errorCodes;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function getFailsDescription(): array
    {
        return $this->failsDescription;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }
}
