<?php
/**
 * Copyright (c) 2021 Hawksearch (www.hawksearch.com) - All Rights Reserved
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
use HawkSearch\Connector\Gateway\Validator\ResultInterfaceFactory;

/**
 * Compiles a result using the results of multiple validators
 */
class ValidatorComposite extends AbstractValidator
{
    /**
     * @var ValidatorInterface[] | TMap
     */
    private $validators;

    /**
     * @var array
     */
    private $chainBreakingValidators;

    /**
     * @param ResultInterfaceFactory $resultFactory
     * @param TMapFactory $tmapFactory
     * @param array $validators
     * @param array $chainBreakingValidators
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

    /**
     * Performs domain level validation for business object
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
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
