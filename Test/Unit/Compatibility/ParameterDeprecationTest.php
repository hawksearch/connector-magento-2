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

use HawkSearch\Connector\Compatibility\DeprecatedMessageBuilderInterface;
use HawkSearch\Connector\Compatibility\DeprecatedMessageTrigger;
use HawkSearch\Connector\Compatibility\DeprecatedMessageTriggerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ParameterDeprecationTest extends TestCase
{
    private DeprecatedMessageBuilderInterface|MockObject $messageBuilderMock;
    private DeprecatedMessageTrigger $messageTrigger;

    protected function setUp(): void
    {
        $this->messageBuilderMock = $this->getMockBuilder(DeprecatedMessageBuilderInterface::class)
            ->onlyMethods(['setSincePart', 'setMainPart', 'setReplacementPart', 'setExtra', 'build'])
            ->getMockForAbstractClass();

        $this->messageTrigger = new DeprecatedMessageTrigger();

        $objectManagerMock = $this->getMockBuilder(ObjectManagerInterface::class)
            ->getMockForAbstractClass();
        $objectManagerMock->method('create')->willReturnMap([
            [DeprecatedMessageBuilderInterface::class, [], $this->messageBuilderMock],
            [DeprecatedMessageTriggerInterface::class, [], $this->messageTrigger]
        ]);
        $objectManagerMock->method('get')->willReturnMap([
            [DeprecatedMessageBuilderInterface::class, $this->messageBuilderMock],
            [DeprecatedMessageTriggerInterface::class, $this->messageTrigger]
        ]);
        ObjectManager::setInstance($objectManagerMock);
    }

    public function testTriggerDeprecationMessage(): void
    {
        $this->messageBuilderMock->expects($this->any())
            ->method('setSincePart')
            ->willReturnSelf();
        $this->messageBuilderMock->expects($this->any())
            ->method('setMainPart')
            ->willReturnSelf();
        $this->messageBuilderMock->expects($this->any())
            ->method('setReplacementPart')
            ->willReturnSelf();
        $this->messageBuilderMock->expects($this->any())
            ->method('setExtra')
            ->willReturnSelf();

        $message = 'deprecated method parameter';
        $this->messageBuilderMock->expects($this->once())
            ->method('build')
            ->willReturn($message);

        set_error_handler(fn() => false);
        $e = error_reporting(0);
        trigger_error('', E_USER_NOTICE);

        $parameterDeprecation = new Fixtures\ParameterDeprecation();
        $parameterDeprecation->deprecatedMethod('test');

        error_reporting($e);
        restore_error_handler();

        $lastError = error_get_last();
        unset($lastError['file'], $lastError['line']);

        $xError = [
            'type' => E_USER_DEPRECATED,
            'message' => $message,
        ];

        $this->assertSame($xError, $lastError);
    }
}
