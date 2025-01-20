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

/**
 * Trait helps to trigger a deprecation error for public/protected properties.
 *
 *  Usage:
 *  - Use this treat for classes which require public/protected properties to be deprecated because of
 *    changing visibility or removing.
 *  - Add the phpDoc tag "@private" to the property (so IDEs understand that).
 *  - Add deprecated public/protected properties to private $deprecatedPublicProperties property in your class.
 *  - Change property visibility to protected or private.
 *  - If property is going to be removed its visibility should be changed to private. It is not allowed to remove
 *    property permanently, becasue setting a value to an undeclared class property is deprecated since PHP 8.2. This
 *    trait does sort out making deprecated private properties still accessible.
 *
 *  Removing deprecation:
 *  - Remove properties from $deprecatedPublicProperties property
 *  - Remove the trait use after removing the last deprecation.
 *  - Remove properties phpDoc tags related to deprecation notice (if any are left)
 *
 * Note:
 *
 *  Use this trait for classes only that do not make use of magic accessors otherwise.
 *
 * Example:
 *
 *  class Example
 *  {
 *       use PublicPropertyDeprecationTrait;
 *
 *       private array $deprecatedPublicProperties = [
 *           'deprecatedProperty' => [
 *               'since' => '1.1.0',
 *               'replacement' => __CLASS__ . '::getDeprecatedProperty()',
 *               'description' => 'Property is going to be removed'
 *           ],
 *           'propertyChangedToPrivate' => [
 *                'since' => '1.1.0',
 *                'replacement' => 'Inject class object in derived constructor'
 *                'description' => 'Visibility changed to private'
 *            ],
 *       ];
 *
 *       /**
 *        * Property visibility changed to private.
 *        *
 *        * internal
 *        * deprecated 1.1.0 Visibility changed to private. Inject class object in derived constructor
 *        /
 *       private mixed $propertyChangedToPrivate;
 *
 *        /**
 *         * Property to be removed.
 *         *
 *         * deprecated 1.1.0
 *         * private
 *         /
 *       protected $deprecatedProperty;
 *  }
 *
 * @property array<string, array{
 *            since: string,
 *            replacement: string,
 *            description: string
 *   }> $deprecatedPublicProperties List of deprecated public/protected properties
 * @internal
 */
trait PublicPropertyDeprecationTrait
{
    /**
     * Verifies whether the specified property is assigned a value
     * This method is not applicable to public properties.
     */
    public function __isset(string $propertyName): bool
    {
        if (isset($this->deprecatedPublicProperties[$propertyName])) {
            $this->triggerPublicPropertyDeprecationMessage($propertyName);
            return isset($this->$propertyName);
        }
        return false;
    }

    /**
     * Retrieves the value of the specified property.
     * It is assumed that this method will not be invoked for a public property.
     */
    public function __get(string $propertyName): mixed
    {
        if (isset($this->deprecatedPublicProperties[$propertyName])) {
            $this->triggerPublicPropertyDeprecationMessage($propertyName);
            return property_exists($this, $propertyName) ? $this->$propertyName : null;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . get_class($this) . '::$' . $propertyName .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);

        return null;
    }

    /**
     * Assigns the value of the specified property.
     * Furthermore, it prohibits the assignment of properties that are not recognized.
     */
    public function __set(string $propertyName, mixed $propertyValue): void
    {
        if (property_exists($this, $propertyName) && isset($this->deprecatedPublicProperties[$propertyName])) {
            $this->triggerPublicPropertyDeprecationMessage($propertyName);
            $this->$propertyName = $propertyValue;
        }

        if (property_exists(__CLASS__, $propertyName) && !property_exists(get_class($this), $propertyName)) {
            if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
                trigger_error(
                    'Creation of dynamic property via __set(): ' . get_class($this) . '::$' . $propertyName . ' is deprecated',
                    E_USER_DEPRECATED);
            }
        } else {
            $this->$propertyName = $propertyValue;
        }
    }

    /**
     * Unset the property.
     */
    public function __unset(string $propertyName): void
    {
        if (isset($this->deprecatedPublicProperties[$propertyName])) {
            $this->triggerPublicPropertyDeprecationMessage($propertyName);
        }
        unset($this->$propertyName);
    }

    /**
     * Trigger a deprecation message for a deprecated property.
     * This method can be triggered from a class public methods which are a part of public contracts
     */
    private function triggerPublicPropertyDeprecationMessage(string $propertyName): void
    {
        $message = $this->buildPropertyDeprecationMessage(
            $propertyName,
            [
                DeprecatedMessageInterface::TEMPLATE_PUBLIC_PROPERTY_MAIN_PART,
                [__CLASS__ . '::' . $propertyName]
            ]
        );

        DeprecationUtility::getMessageTrigger()->execute($message);
    }

    /**
     * @param string $propertyName
     * @param mixed[] $mainPartArgs
     * @return string
     */
    private function buildPropertyDeprecationMessage(string $propertyName, array $mainPartArgs): string
    {
        $messageBuilder = DeprecationUtility::getMessageBuilder();
        if (isset($this->deprecatedPublicProperties[$propertyName])) {
            $messageBuilder->setMainPart(...$mainPartArgs);
        }

        if (isset($this->deprecatedPublicProperties[$propertyName]['since'])) {
            $messageBuilder->setSincePart(
                DeprecatedMessageInterface::TEMPLATE_SINCE_PART,
                [$this->deprecatedPublicProperties[$propertyName]['since']]
            );
        }

        if (isset($this->deprecatedPublicProperties[$propertyName]['replacement'])) {
            $messageBuilder->setReplacementPart(
                DeprecatedMessageInterface::TEMPLATE_REPLACEMENT_PART,
                [$this->deprecatedPublicProperties[$propertyName]['replacement']]
            );
        }

        if (isset($this->deprecatedPublicProperties[$propertyName]['description'])) {
            $messageBuilder->setExtra(
                $this->deprecatedPublicProperties[$propertyName]['description']
            );
        }

        return $messageBuilder->build();
    }
}
