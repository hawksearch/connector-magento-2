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
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeprecatedMessageBuilderTest extends TestCase
{
    private MockObject|DataObjectFactory $dataObjectFactoryMock;
    private DeprecatedMessageBuilder $model;

    protected function setUp(): void
    {
        $this->dataObjectFactoryMock = $this->getMockBuilder(DataObjectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->model = new DeprecatedMessageBuilder($this->dataObjectFactoryMock);
    }

    /**
     * @dataProvider dataProviderBuild
     */
    public function testBuild(array $formats, array $parts, string $result): void
    {
        $this->dataObjectFactoryMock->expects($this->any())->method('create')
            ->willReturnCallback(function ($args) {
                return new DataObject(...$args);
            });

        $partsSet = array_keys($formats);
        foreach ($partsSet as $partName) {
            $partValues = $parts[$partName] ?? [];
            $methodName = 'set' . ucfirst($partName) . ($partName !== 'extra' ? 'Part' : '');
            $this->model->$methodName($formats[$partName], $partValues);
        }

        $this->assertEquals($result, $this->model->build());
    }

    public function testBuildWithInvalidFormatException(): void
    {
        $format = "There are two conversion specifiers: %s and %s. It is expected to have two arguments, but only one is given.";
        $values = ['First argument only'];

        $this->dataObjectFactoryMock->expects($this->once())->method('create')
            ->willReturnCallback(function ($args) {
                return new DataObject(...$args);
            });
        $this->model->setExtra($format, $values);

        $this->expectException(\ArgumentCountError::class);

        $this->model->build();
    }

    public function dataProviderBuild(): array
    {
        return [
            'All Parts Set' => [
                // format
                [
                    'since' => 'Since %s:',
                    'main' => 'Some %s and %s are deprecated.',
                    'replacement' => 'Use %s instead.',
                    'extra' => 'Extra format without conversion specifier',
                ],
                // parts
                [
                    'since' => ['1.0.0'],
                    'main' => ['TestingClass', 'TheOtherTestingClass'],
                    'replacement' => ['AnotherTestingClass'],
                    'extra' => ['This is is not rendered'],
                ],
                // result
                'Since 1.0.0: Some TestingClass and TheOtherTestingClass are deprecated. Use AnotherTestingClass instead. Extra format without conversion specifier'
            ],
            'No Replacement, No Extra parts' => [
                [
                    'since' => 'Since %s:',
                    'main' => 'Some %s and %s are deprecated.',
                    'extra' => '',
                ],
                [
                    'since' => ['1.0.0'],
                    'main' => ['TestingClass', 'TheOtherTestingClass'],
                ],
                'Since 1.0.0: Some TestingClass and TheOtherTestingClass are deprecated.'
            ],
            'Only Extra part set' => [
                [
                    'extra' => 'Deprecation notice put in Extra: %s.',
                ],
                [
                    'extra' => ['Rendered notice', 'Not rendered notice'],
                ],
                'Deprecation notice put in Extra: Rendered notice.'
            ],
            'No parts' => [
                [],
                [],
                ''
            ]
        ];
    }
}
