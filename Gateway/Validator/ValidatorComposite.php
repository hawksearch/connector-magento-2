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
namespace HawkSearch\Connector\Gateway\Validator;

use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

/**
 * Compiles the results from multiple validators
 *
 * @api
 *
 * @template TKey of array-key
 * @template TValue of ValidatorInterface
 */
class ValidatorComposite extends AbstractValidator
{
    /**
     * @var TMap<TKey, TValue>
     */
    private $validators;

    /**
     * @var array<array-key, TKey>
     */
    private $chainBreakingValidators;

    /**
     * @param ResultInterfaceFactory $resultFactory
     * @param TMapFactory $tmapFactory
     * @param array<TKey, class-string<TValue>> $validators
     * @param array<array-key, TKey> $chainBreakingValidators
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        TMapFactory $tmapFactory,
        array $validators = [],
        array $chainBreakingValidators = []
    ) {
        $this->validators = $tmapFactory->create(
            [
                'array' => $validators,
                'type' => ValidatorInterface::class
            ]
        );
        $this->chainBreakingValidators = $chainBreakingValidators;
        parent::__construct($resultFactory);
    }

    public function validate(array $validationSubject): ResultInterface
    {
        $isValid = true;
        $failsDescriptionAggregate = [];
        $errorCodesAggregate = [];
        foreach ($this->validators as $key => $validator) {
            $result = $validator->validate($validationSubject);
            if (!$result->isValid()) {
                $isValid = false;
                $failsDescriptionAggregate = array_merge(
                    $failsDescriptionAggregate,
                    $result->getFailsDescription()
                );
                $errorCodesAggregate = array_merge(
                    $errorCodesAggregate,
                    $result->getErrorCodes()
                );
                if (!empty($this->chainBreakingValidators[$key])) {
                    break;
                }
            }
        }

        return $this->createResult($isValid, $failsDescriptionAggregate, $errorCodesAggregate);
    }
}
