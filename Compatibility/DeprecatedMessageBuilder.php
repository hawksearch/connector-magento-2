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

use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;

/**
 * @internal
 */
class DeprecatedMessageBuilder
    extends AbstractSimpleObject
    implements DeprecatedMessageBuilderInterface
{
    private const PARTS_ORDER = [
        self::PART_SINCE,
        self::PART_MAIN,
        self::PART_REPLACEMENT,
        self::PART_EXTRA,
    ];


    private DataObjectFactory $dataObjectFactory;

    /**
     * @param DataObjectFactory $dataObjectFactory
     * @param array<string, mixed> $data
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->dataObjectFactory = $dataObjectFactory;
    }

    public function build(): string
    {
        $messageTemplateParts = [];

        foreach (self::PARTS_ORDER as $part) {
            /** @var ?DataObject $partObject */
            $partObject = $this->_get($part);
            if (!$partObject || !$partObject->getFormat()) {
                continue;
            }
            $messageTemplateParts[] = sprintf($partObject->getFormat(), ...$partObject->getValues());
        }

        $this->_data = [];
        return implode(' ', $messageTemplateParts);
    }

    public function setSincePart(string $format, array $values = []): DeprecatedMessageBuilderInterface
    {
        return $this->setData(
            self::PART_SINCE,
            $this->getPartObject($format, $values)
        );
    }

    public function setMainPart(string $format, array $values = []): DeprecatedMessageBuilderInterface
    {
        return $this->setData(
            self::PART_MAIN,
            $this->getPartObject($format, $values)
        );
    }

    public function setReplacementPart(string $format, array $values = []): DeprecatedMessageBuilderInterface
    {
        return $this->setData(
            self::PART_REPLACEMENT,
            $this->getPartObject($format, $values)
        );
    }

    public function setExtra(string $format, array $values = []): DeprecatedMessageBuilderInterface
    {
        return $this->setData(
            self::PART_EXTRA,
            $this->getPartObject($format, $values)
        );
    }

    /**
     * @param string $format
     * @param string[] $values
     */
    private function getPartObject(string $format, array $values = []): DataObject
    {
        return $this->dataObjectFactory->create(
            [
                'data' => [
                    'format' => $format,
                    'values' => $values,
                ]
            ]
        );
    }
}
