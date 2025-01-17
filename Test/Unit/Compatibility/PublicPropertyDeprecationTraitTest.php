<?php
/**
 * Copyright (c) 2025 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Test\Unit\Compatibility;

use HawkSearch\Connector\Compatibility\DeprecatedMessageBuilder;
use HawkSearch\Connector\Compatibility\DeprecatedMessageBuilderInterface;
use HawkSearch\Connector\Compatibility\DeprecatedMessageTrigger;
use HawkSearch\Connector\Compatibility\DeprecatedMessageTriggerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

//use PHPUnit\Framework\Attributes\RequiresPhp;

class PublicPropertyDeprecationTraitTest extends TestCase
{
    private array $deprecatedPublicProperties = [
        'propertyDeprecatedChangedToPrivateScope' => [
            'since' => '1.1.0',
            'replacement' => 'Fixtures\PublicPropertyDeprecationBase' . '::getDeprecatedProperty()',
            'description' => 'Property is going to be removed'
        ],
        'propertyDeprecatedChangedToProtectedScope' => [
        ],
        'nonExistentButAnnotatedProperty' => [
        ],

    ];

    private MockObject|DataObjectFactory $dataObjectFactoryMock;
    private DeprecatedMessageBuilder $messageBuilder;
    private DeprecatedMessageTrigger $messageTrigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataObjectFactoryMock = $this->getMockBuilder(DataObjectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageBuilder = new DeprecatedMessageBuilder($this->dataObjectFactoryMock);
        $this->messageTrigger = new DeprecatedMessageTrigger();

        $objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->getMockForAbstractClass();
        $objectManagerMock->method('create')->willReturnMap([
            [DeprecatedMessageBuilderInterface::class, [], $this->messageBuilder],
            [DeprecatedMessageTriggerInterface::class, [], $this->messageTrigger]
        ]);
        $objectManagerMock->method('get')->willReturnMap([
            [DeprecatedMessageBuilderInterface::class, $this->messageBuilder],
            [DeprecatedMessageTriggerInterface::class, $this->messageTrigger]
        ]);
        ObjectManager::setInstance($objectManagerMock);
    }

    public function testCreateClassObjectDoesNotTriggerNotices(): void
    {
        set_error_handler(fn() => false);
        $e = error_reporting(0);
        trigger_error('', E_USER_NOTICE);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $object = new Fixtures\PublicPropertyDeprecationBase($this->deprecatedPublicProperties);

        error_reporting($e);
        restore_error_handler();

        $lastError = error_get_last();
        unset($lastError['file'], $lastError['line']);

        $xError = [
            'type' => E_USER_NOTICE,
            'message' => '',
        ];

        $this->assertSame($xError, $lastError);
    }

    public function testDeprecationsWhenAccessingProperties(): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $baseObject = new Fixtures\PublicPropertyDeprecationBase($this->deprecatedPublicProperties);

        // test access to private deprecated property
        $this->assertSame('initial private value', $baseObject->propertyDeprecatedChangedToPrivateScope);
        $this->assertTrue(isset($baseObject->propertyDeprecatedChangedToPrivateScope));
        $baseObject->propertyDeprecatedChangedToPrivateScope = 'changed value';
        $this->assertSame('changed value', $baseObject->propertyDeprecatedChangedToPrivateScope);
        $this->assertSame('changed value', $baseObject->accessDeprecatedPrivateFromPublicMethodNoDeprecationNotice());

        // test access to protected deprecated property
        $this->assertSame('initial protected value', $baseObject->propertyDeprecatedChangedToProtectedScope);
        $this->assertTrue(isset($baseObject->propertyDeprecatedChangedToProtectedScope));
        $baseObject->propertyDeprecatedChangedToProtectedScope = 'changed value';
        $this->assertSame('changed value', $baseObject->propertyDeprecatedChangedToProtectedScope);
        $this->assertSame('changed value', $baseObject->accessDeprecatedProtectedFromPublicMethodNoDeprecationNotice());

        $derivedObject = new Fixtures\PublicPropertyDeprecationDerived($this->deprecatedPublicProperties);

        $this->assertSame('regular', $derivedObject->accessRegularProtectedPropertyFromDerivedClassNoDeprecationNotice());
        $this->assertSame('initial protected value', $derivedObject->accessDeprecatedProtectedPropertyFromDerivedClassNoDeprecationNotice());
        $this->assertSame('initial private value', $derivedObject->accessDeprecatedPrivatePropertyFromDerivedClass());

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
            "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToProtectedScope has been deprecated and it's public/protected usage will be discontinued.",
            "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToProtectedScope has been deprecated and it's public/protected usage will be discontinued.",
            "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToProtectedScope has been deprecated and it's public/protected usage will be discontinued.",
            "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToProtectedScope has been deprecated and it's public/protected usage will be discontinued.",
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
        ], $deprecations);
    }

    /**
     * @dataProvider getDeprecationsAndExceptionsWhenUnsettingPropertiesDataProvider
     */
    public function testDeprecationsAndExceptionsWhenUnsettingProperties(string $property, ?string $value, bool $expectedException, array $deprecationsAssertion): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $baseObject = new Fixtures\PublicPropertyDeprecationBase($this->deprecatedPublicProperties);

        $this->assertSame($value, $baseObject->$property);
        unset($baseObject->$property);

        error_reporting($e);
        restore_error_handler();

        $this->assertSame($deprecationsAssertion, $deprecations);

        if ($expectedException) {
            $this->expectException(\Error::class);
        }

        $this->assertNull($baseObject->$property);
    }

    public function getDeprecationsAndExceptionsWhenUnsettingPropertiesDataProvider(): array
    {
        return [
            'propertyDeprecatedChangedToPrivateScope' => [
                // $property
                'propertyDeprecatedChangedToPrivateScope',
                // $value
                'initial private value',
                // $expectedException
                true,
                //$deprecationsAssertion
                [
                    "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
                    "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
                ]
            ],
            'propertyDeprecatedChangedToProtectedScope' => [
                'propertyDeprecatedChangedToProtectedScope',
                'initial protected value',
                true,
                [
                    "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToProtectedScope has been deprecated and it's public/protected usage will be discontinued.",
                    "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToProtectedScope has been deprecated and it's public/protected usage will be discontinued.",
                ]
            ],
            'nonExistentButAnnotatedProperty' => [
                'nonExistentButAnnotatedProperty',
                null,
                false,
                [
                    "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::nonExistentButAnnotatedProperty has been deprecated and it's public/protected usage will be discontinued.",
                    "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::nonExistentButAnnotatedProperty has been deprecated and it's public/protected usage will be discontinued.",
                ]
            ],

        ];
    }

    /**
     * @requires PHP <8.2.0
     */
    #[RequiresPhp('<8.2.0')]
    public function testDeprecationsAccessingNonExistentPropertiesPhp81(): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $baseObject = new Fixtures\PublicPropertyDeprecationBase($this->deprecatedPublicProperties);

        // test access to non-existent property
        $this->assertNull($baseObject->nonExistentButAnnotatedProperty);
        $baseObject->nonExistentButAnnotatedProperty = 'changed value';
        $this->assertSame('changed value', $baseObject->nonExistentButAnnotatedProperty);

        $baseObject->nonExistentProperty = 'changed value';
        $this->assertSame('changed value', $baseObject->nonExistentProperty);

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::nonExistentButAnnotatedProperty has been deprecated and it's public/protected usage will be discontinued.",
        ], $deprecations);
    }

    /**
     * @requires PHP >=8.2.0
     */
    #[RequiresPhp('>=.2.0')]
    public function testDeprecationsAccessingNonExistentPropertiesPhp82(): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $baseObject = new Fixtures\PublicPropertyDeprecationBase($this->deprecatedPublicProperties);

        // test access to non-existent property
        $this->assertNull($baseObject->nonExistentButAnnotatedProperty);
        $baseObject->nonExistentButAnnotatedProperty = 'changed value';
        $this->assertSame('changed value', $baseObject->nonExistentButAnnotatedProperty);

        $baseObject->nonExistentProperty = 'changed value';
        $this->assertSame('changed value', $baseObject->nonExistentProperty);

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            "Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::nonExistentButAnnotatedProperty has been deprecated and it's public/protected usage will be discontinued.",
            "Creation of dynamic property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::\$nonExistentButAnnotatedProperty is deprecated",
            "Creation of dynamic property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::\$nonExistentProperty is deprecated",
        ], $deprecations);
    }

    /**
     * @requires PHP <8.2.0
     */
    #[RequiresPhp('<8.2.0')]
    public function testSetPrivatePropertyFromDerivedClassPhp81(): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $derivedObject = new Fixtures\PublicPropertyDeprecationDerived($this->deprecatedPublicProperties);

        $derivedObject->setProperty('propertyDeprecatedChangedToPrivateScope', 'from derived class');
        $derivedObject->setProperty('regularPrivateProperty', 'from derived class');

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
        ], $deprecations);
    }

    /**
     * @requires PHP >=8.2.0
     */
    #[RequiresPhp('>=8.2.0')]
    public function testSetPrivatePropertyFromDerivedClassPhp82(): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $derivedObject = new Fixtures\PublicPropertyDeprecationDerived($this->deprecatedPublicProperties);

        $derivedObject->setProperty('propertyDeprecatedChangedToPrivateScope', 'from derived class');
        $derivedObject->setProperty('regularPrivateProperty', 'from derived class');

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            "Since 1.1.0: Property HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::propertyDeprecatedChangedToPrivateScope has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicPropertyDeprecationBase::getDeprecatedProperty() instead. Property is going to be removed",
            "Creation of dynamic property via __set(): HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationDerived::\$propertyDeprecatedChangedToPrivateScope is deprecated",
            "Creation of dynamic property via __set(): HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationDerived::\$regularPrivateProperty is deprecated",
        ], $deprecations);
    }

    public function testNonExistentPropertyException(): void
    {
        $this->initDataObjectFactory();

        set_error_handler(fn() => false);
        $e = error_reporting(0);
        trigger_error('', E_USER_NOTICE);

        require_once __DIR__ . '/Fixtures/PublicPropertyDeprecationFixture.php';
        $baseObject = new Fixtures\PublicPropertyDeprecationBase($this->deprecatedPublicProperties);

        $this->assertNull($baseObject->nonExistentProperty);
        $this->assertFalse(isset($baseObject->nonExistentProperty));
        unset($baseObject->nonExistentProperty);

        error_reporting($e);
        restore_error_handler();

        $lastError = error_get_last();
        unset($lastError['file'], $lastError['line']);

        $xError = [
            'type' => E_USER_NOTICE,
            'message' => 'Undefined property via __get(): HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicPropertyDeprecationBase::$nonExistentProperty',
        ];

        $this->assertSame($xError['type'], $lastError['type']);
        $this->assertStringStartsWith($xError['message'], $lastError['message']);
    }

    private function initDataObjectFactory(): void
    {
        $this->dataObjectFactoryMock->expects($this->any())->method('create')
            ->willReturnCallback(function ($args) {
                return new DataObject(...$args);
            });
    }
}
