<?php
/**
 * Copyright (c) 2024 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Compatibility;

use Magento\Framework\App\ObjectManager;

/**
 * Trait helps to trigger a deprecation error for public methods of classes.
 *
 * Usage:
 * - Use this treat for classes which require one or more public methods to be deprecated because of changing method
 *   visibility or removing.
 * - Add deprecated public methods to $deprecatedMethods property.
 * - Set these methods to protected or private. If it is a public method of an interface, don't change method visibility.
 *
 * Removing deprecation:
 * - Remove methods from $deprecatedMethods property
 * - Remove the trait use after removing the last deprecation.
 *
 * Example:
 *
 * class Example
 * {
 *      use PublicMethodDeprecationTrait;
 *
 *      private $deprecatedMethods = [
 *          'doSomeAction' => [
 *              'since' => '1.1.0',
 *              'replacement' => __CLASS__ . '::doAnotherAction()',
 *              'description' => 'Additional deprecation information if exists.'
 *          ],
 *          'publicMethod' => [
 *               'since' => '1.1.0',
 *               'replacement' => __CLASS__ . '::doAnotherAction()',
 *               'description' => 'Method will be removed'
 *           ],
 *      ];
 *
 *      /**
 *       * Method to be deprecated.
 *       *
 *       * deprecated 1.1.0 Use doAnotherAction() instead
 *       * see $this::doAnotherAction()
 *       /
 *      private function doSomeAction($arg1, $arg2)
 *      {
 *          //method scope has been changed from public to private
 *      }
 *
 *       /**
 *        * Method to be removed.
 *        *
 *        * deprecated 1.1.0
 *        /
 *      public function publicMethod()
 *      {
 *          $this->triggerPublicMethodDeprecationMessage(__FUNCTION__);
 *      }
 * }
 *
 * @property array $deprecatedMethods List of deprecated public methods
 * @internal
 */
trait PublicMethodDeprecationTrait
{
    /**
     * Triggers a deprecation message for a callable (public or private) method and execute it.
     * If method isn't callable it throws a fatal error.
     *
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $methodName, array $arguments)
    {
        if (method_exists($this, $methodName) && isset($this->deprecatedMethods[$methodName])) {
            $this->triggerPublicMethodDeprecationMessage($methodName);
            return $this->$methodName(...$arguments);
        }

        if (method_exists($this, $methodName)) {
            throw new \Error('Call to protected/private method ' . __CLASS__ . '::' . $methodName . '()');
        }

        throw new \Error('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()');
    }

    /**
     * Trigger a deprecation message.
     * This method can be triggered from a class public methods which are a part of public contracts
     *
     * @param string $methodName
     * @return void
     */
    protected function triggerPublicMethodDeprecationMessage(string $methodName)
    {
        $messageBuilder = DeprecationUtility::getMessageBuilder();
        if (isset($this->deprecatedMethods[$methodName])) {
            $messageBuilder->setMainPart(
                DeprecatedMessageInterface::TEMPLATE_PUBLIC_METHOD_MAIN_PART,
                [__CLASS__ . '::' . $methodName . '()']
            );
        }

        if (isset($this->deprecatedMethods[$methodName]['since'])) {
            $messageBuilder->setSincePart(
                DeprecatedMessageInterface::TEMPLATE_SINCE_PART,
                [$this->deprecatedMethods[$methodName]['since']]
            );
        }

        if (isset($this->deprecatedMethods[$methodName]['replacement'])) {
            $messageBuilder->setReplacementPart(
                DeprecatedMessageInterface::TEMPLATE_REPLACEMENT_PART,
                [$this->deprecatedMethods[$methodName]['replacement']]
            );
        }

        if (isset($this->deprecatedMethods[$methodName]['description'])) {
            $messageBuilder->setExtra(
                $this->deprecatedMethods[$methodName]['description']
            );
        }

        DeprecationUtility::getMessageTrigger()->execute($messageBuilder->build());
    }
}
