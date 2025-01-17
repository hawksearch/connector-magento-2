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

class PublicMethodDeprecationTraitTest extends TestCase
{
    private array $deprecatedMethods = [
        'doSomeDeprecatedActionChangedVisibilityToPrivate' => [
            'since' => '1.1.0',
            'replacement' => 'Fixtures\PublicMethodDeprecationBase' . '::doAnotherAction()',
            'description' => ''
        ],
        'doSomeDeprecatedActionChangedVisibilityToProtected' => [

        ],
        'doPublicJustOnlyAnnotatedAction' => [
            'since' => '1.1.0',
            'description' => 'Method is public. No deprecation notice'
        ],
        'doPublicTriggeredAndAnnotatedAction' => [
        ],
        'doOverwrittenDeprecatedAction' => [
            'since' => '1.1.0',
            'description' => ''
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

        require_once __DIR__ . '/Fixtures/PublicMethodDeprecationFixture.php';
        $object = new Fixtures\PublicMethodDeprecationBase($this->deprecatedMethods);

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

    public function testDeprecations(): void
    {
        $this->initDataObjectFactory();

        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) {
            $deprecations[] = $msg;
        });
        $e = error_reporting(E_USER_DEPRECATED);

        require_once __DIR__ . '/Fixtures/PublicMethodDeprecationFixture.php';
        $baseObject = new Fixtures\PublicMethodDeprecationBase($this->deprecatedMethods);

        $this->assertSame('My argument is called', $baseObject->doSomeDeprecatedActionChangedVisibilityToPrivate('My argument'));
        $this->assertSame('My argument is called', $baseObject->doSomeDeprecatedActionChangedVisibilityToProtected('My argument'));
        $this->assertSame('callDeprecatedPrivateFromPublicMethod is called', $baseObject->callDeprecatedPrivateFromPublicMethod());

        $baseObject->doRegularPublicAction();
        $baseObject->doPublicJustOnlyAnnotatedAction();
        $baseObject->doPublicTriggeredNotAnnotatedAction();
        $baseObject->doPublicTriggeredAndAnnotatedAction();

        $derivedObject = new Fixtures\PublicMethodDeprecationDerived($this->deprecatedMethods);

        $this->assertSame('callDeprecatedPrivateMethodFromDerivedClass is called', $derivedObject->callDeprecatedPrivateMethodFromDerivedClass());
        $this->assertSame('callDeprecatedProtectedMethodFromDerivedClass is called', $derivedObject->callDeprecatedProtectedMethodFromDerivedClass());

        $this->assertSame('test in derived class', $derivedObject->doOverwrittenDeprecatedAction('test'));
        $this->assertSame('From collectDerivedActions method', $derivedObject->callDerivedDeprecatedAction());


        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            "Since 1.1.0: Method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doSomeDeprecatedActionChangedVisibilityToPrivate() has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicMethodDeprecationBase::doAnotherAction() instead.",
            "Method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doSomeDeprecatedActionChangedVisibilityToProtected() has been deprecated and it's public/protected usage will be discontinued.",
            "Method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doPublicTriggeredAndAnnotatedAction() has been deprecated and it's public/protected usage will be discontinued.",
            "Since 1.1.0: Method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doSomeDeprecatedActionChangedVisibilityToPrivate() has been deprecated and it's public/protected usage will be discontinued. Use Fixtures\PublicMethodDeprecationBase::doAnotherAction() instead.",
            "Since 1.1.0: Method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doOverwrittenDeprecatedAction() has been deprecated and it's public/protected usage will be discontinued.",
            "Since 1.1.0: Inheritance of HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationDerived::doOverwrittenDeprecatedAction() method is deprecated because of deprecation of the base method."
        ], $deprecations);
    }

    /**
     * @dataProvider getExceptionsDataProvider
     */
    public function testExceptions(string $methodName, string $message): void
    {
        $this->initDataObjectFactory();

        require_once __DIR__ . '/Fixtures/PublicMethodDeprecationFixture.php';
        $object = new Fixtures\PublicMethodDeprecationBase($this->deprecatedMethods);

        $this->expectException(\Error::class);
        $this->expectExceptionMessage($message);
        $object->$methodName();
    }

    public function getExceptionsDataProvider(): array
    {
        return [
            'Call private method' => [
                'doPrivateAction',
                'Call to protected/private method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doPrivateAction()'
            ],
            'Call protected method' => [
                'doProtectedAction',
                'Call to protected/private method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doProtectedAction()'
            ],
            'Call undefined method' => [
                'doUndefinedAction',
                'Call to undefined method HawkSearch\Connector\Test\Unit\Compatibility\Fixtures\PublicMethodDeprecationBase::doUndefinedAction()'
            ]
        ];
    }

    private function initDataObjectFactory(): void
    {
        $this->dataObjectFactoryMock->expects($this->any())->method('create')
            ->willReturnCallback(function ($args) {
                return new DataObject(...$args);
            });
    }
}
