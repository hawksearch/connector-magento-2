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

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

/**
 * @api
 * @since 2.11
 */
class InstructionManagerPool implements InstructionManagerPoolInterface
{
    /**
     * @var InstructionManagerInterface[] | TMap
     */
    private $executors;

    /**
     * @param TMapFactory $tmapFactory
     * @param array $executors
     */
    public function __construct(
        TMapFactory $tmapFactory,
        array $executors = []
    ) {
        $this->executors = $tmapFactory->createSharedObjectsMap(
            [
                'array' => $executors,
                'type' => InstructionManagerInterface::class
            ]
        );
    }

    /**
     * Returns Instruction executor for defined provider
     *
     * @param string $instructionProviderCode
     * @return InstructionManagerInterface
     * @throws NotFoundException
     */
    public function get($instructionProviderCode)
    {
        if (!isset($this->executors[$instructionProviderCode])) {
            throw new NotFoundException(
                __(
                    'The "%1" instruction executor isn\'t defined. Verify the executor and try again.',
                    $instructionProviderCode
                )
            );
        }

        return $this->executors[$instructionProviderCode];
    }
}
