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

trait LegacyBaseTrait
{
    private MockObject|DataObjectFactory $dataObjectFactoryMock;
    /**
     * @var list<string>
     */
    protected array $deprecations = [];
    private int $errorReporting;

    /**
     * Use it in testing legacy functionality in very beginingn of a test method.
     */
    private function setUpLegacy(TestCase $testCase): void
    {
        $this->dataObjectFactoryMock = $testCase->getMockBuilder(DataObjectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBuilder = new DeprecatedMessageBuilder($this->dataObjectFactoryMock);
        $messageTrigger = new DeprecatedMessageTrigger();

        /** @var MockObject|ObjectManagerInterface $objectManagerMock */
        $objectManagerMock = $testCase->getMockBuilder(ObjectManagerInterface::class)
            ->getMockForAbstractClass();
        $objectManagerMock->method('create')->willReturnMap([
            [DeprecatedMessageBuilderInterface::class, [], $messageBuilder],
            [DeprecatedMessageTriggerInterface::class, [], $messageTrigger]
        ]);
        $objectManagerMock->method('get')->willReturnMap([
            [DeprecatedMessageBuilderInterface::class, $messageBuilder],
            [DeprecatedMessageTriggerInterface::class, $messageTrigger]
        ]);
        ObjectManager::setInstance($objectManagerMock);

        $this->dataObjectFactoryMock->expects($testCase->any())->method('create')
            ->willReturnCallback(function ($args) {
                return new DataObject(...$args);
            });

        $this->deprecations = [];
        set_error_handler(function (int $type, string $msg): bool {
            $this->deprecations[] = $msg;
            return true;
        });
        
        $this->errorReporting = error_reporting(E_USER_DEPRECATED);
    }

    /**
     * Use it in testing legacy functionality in very end of a test method.
     */
    private function tearDownLegacy(TestCase $testCase): void
    {
        error_reporting($this->errorReporting);
        restore_error_handler();
    }

    private function assertLegacyProperty(
        string $property,
        mixed $newValue,
        object $model,
        TestCase $testCase,
        array $deprecationsTriggered
    ): void {
        $oldValue = $model->getPropertyToTest($property);

        $model->setPropertyToTest($property, $newValue);
        $testCase::assertSame($newValue, $model->getPropertyToTest($property));
        $testCase::assertNotSame($oldValue, $newValue);

        $testCase::assertTrue($model->issetPropertyToTest($property));
        $model->unsetPropertyToTest($property);
        $testCase::assertFalse($model->issetPropertyToTest($property));

        $testCase::assertSame($deprecationsTriggered, $this->deprecations);
    }
}
