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

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use ReflectionException;

/**
 * Trait helps to trigger a deprecation error for public/protected methods.
 *
 * Usage:
 * - Use this treat for classes which require one or more public/protected methods to be deprecated because of changing
 *   method visibility or removing.
 * - Add deprecated public/protected methods to $deprecatedMethods property in your class.
 * - Change method visibility to protected or private. If it is a public method of an interface, don't change method
 *   visibility.
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
 *      private array $deprecatedMethods = [
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
 *      private function doSomeAction($arg1, $arg2): void
 *      {
 *          //method scope has been changed from public to private
 *      }
 *
 *       /**
 *        * Method to be removed.
 *        *
 *        * deprecated 1.1.0
 *        /
 *      public function publicMethod(): void
 *      {
 *          $this->triggerPublicMethodDeprecationMessage(__FUNCTION__);
 *      }
 * }
 *
 * @property array<string, array{
 *          since: string,
 *          replacement: string,
 *          description: string
 * }> $deprecatedMethods List of deprecated public/protected methods
 * @internal
 */
trait PublicMethodDeprecationTrait
{
    /**
     * Triggers a deprecation message for a callable(protected or private) method and execute it.
     * If method isn't callable it throws a fatal error.
     * Method signature is in sync with {@see DataObject::__call}
     * @return mixed
     */
    public function __call($methodName, $arguments) // @phpstan-ignore-line
    {
        if (method_exists($this, $methodName) && isset($this->deprecatedMethods[$methodName])) {
            $this->triggerPublicMethodDeprecationMessage($methodName);
            return $this->$methodName(...$arguments);
        }

        $throwUnknownMethodException = false;
        $exception = null;
        if ($this instanceof \Magento\Framework\DataObject) {
            try {
                return parent::__call($methodName, $arguments); // @phpstan-ignore-line
            } catch (LocalizedException $exception) {
                $throwUnknownMethodException = true;
            }
        } else {
            $throwUnknownMethodException = true;
        }

        /** @phpstan-ignore if.alwaysTrue */
        if ($throwUnknownMethodException) {
            if ($exception) {
                throw $exception;
            } else {
                if (method_exists($this, $methodName)) {
                    throw new \Error('Call to protected/private method ' . __CLASS__ . '::' . $methodName . '()');
                }

                throw new \Error('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()');
            }
        }
    }

    /**
     * Trigger a deprecation message for a deprecated method.
     * This method can be triggered from a class public methods which are a part of public contracts
     */
    private function triggerPublicMethodDeprecationMessage(string $methodName): void
    {
        $message = $this->buildMethodDeprecationMessage(
            $methodName,
            [
                DeprecatedMessageInterface::TEMPLATE_PUBLIC_METHOD_MAIN_PART,
                [__CLASS__ . '::' . $methodName . '()']
            ]
        );

        DeprecationUtility::getMessageTrigger()->execute($message);
    }

    /**
     * Trigger a deprecation message for a derived method when the base class method is deprecated.
     * Usually it is called from inside a base class in the point where base method is called.
     * Base method should be added to $deprecatedMethods class property.
     *
     * Example:
     * public function main()
     * {
     *      if ($this->isMethodOverwritten('deprecatedMethodName')) {
     *          $this->triggerDerivedMethodDeprecationMessage('deprecatedMethodName');
     *          $value = $this->deprecatedMethodName();
     *      } else {
     *          $value = $this->newMethodName();
     *      }
     * }
     *
     * protected function deprecatedMethodName()
     * {
     *      $this->triggerPublicMethodDeprecationMessage(__FUNCTION__);
     *      return $this->newMethodName();
     * }
     */
    private function triggerDerivedMethodDeprecationMessage(string $methodName): void
    {
        if (!$this->isMethodOverwritten($methodName)) {
            return;
        }

        $message = $this->buildMethodDeprecationMessage(
            $methodName,
            [
                DeprecatedMessageInterface::TEMPLATE_DERIVED_METHOD_MAIN_PART,
                [get_class($this) . '::' . $methodName . '()']
            ]
        );

        DeprecationUtility::getMessageTrigger()->execute($message);
    }

    /**
     * Trigger a deprecation message for a new method and propagate  method usage.
     * Usually it is called from a caller method where new method is called.
     * New method should be added to $deprecatedMethods class property.
     *
     *  Example:
     *  public function main()
     *  {
     *       if (method_exists($this, 'newMethodName')) {
     *           $this->newMethodName();
     *       } else {
     *           $this->triggerNewMethodPropagationDeprecationMessage('newMethodName');
     *       }
     *  }
     */
    private function triggerNewMethodPropagationDeprecationMessage(string $methodName): void
    {
        $message = $this->buildMethodDeprecationMessage(
            $methodName,
            [
                DeprecatedMessageInterface::TEMPLATE_NEW_METHOD_MAIN_PART,
                [__CLASS__, $methodName]
            ]
        );

        DeprecationUtility::getMessageTrigger()->execute($message);
    }

    /**
     * @param string $methodName
     * @param string[] $mainPartArgs
     */
    private function buildMethodDeprecationMessage(string $methodName, array $mainPartArgs): string
    {
        $messageBuilder = DeprecationUtility::getMessageBuilder();
        if (isset($this->deprecatedMethods[$methodName])) {
            $messageBuilder->setMainPart(...$mainPartArgs);
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

        return $messageBuilder->build();
    }

    /**
     * @throws ReflectionException
     */
    private function isMethodOverwritten(string $methodName): bool
    {
        $reflector = new \ReflectionMethod($this, $methodName);
        return $reflector->getDeclaringClass()->getName() !== self::class;
    }
}
