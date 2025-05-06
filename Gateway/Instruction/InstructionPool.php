<?php
/**
 * Copyright (c) 2023 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Gateway\Instruction;

use HawkSearch\Connector\Gateway\InstructionInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

/**
 * Pool of available Instructions
 *
 * @api
 *
 * @template TKey of string
 * @template TValue of InstructionInterface
 * @implements InstructionPoolInterface<TKey, TValue>
 */
class InstructionPool implements InstructionPoolInterface
{
    /**
     * @var TMap<TKey, TValue>
     */
    private TMap $instructions;

    /**
     * @param TMapFactory $tmapFactory
     * @param array<TKey, class-string<TValue>> $instructions
     */
    public function __construct(
        TMapFactory $tmapFactory,
        array $instructions = []
    ) {
        $this->instructions = $tmapFactory->create(
            [
                'array' => $instructions,
                'type' => InstructionInterface::class
            ]
        );
    }

    /**
     * @return InstructionInterface
     * @throws NotFoundException if instruction is not found
     */
    public function get(string $instructionCode)
    {
        if (!isset($this->instructions[$instructionCode])) {
            throw new NotFoundException(
                __('The "%1" instruction doesn\'t exist.', $instructionCode)
            );
        }

        return $this->instructions[$instructionCode];
    }
}
