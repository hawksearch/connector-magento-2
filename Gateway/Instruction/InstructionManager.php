<?php
/**
 *  Copyright (c) 2020 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 *  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 *  IN THE SOFTWARE.
 */
declare(strict_types=1);

namespace HawkSearch\Connector\Gateway\Instruction;

use HawkSearch\Connector\Gateway\InstructionInterface;
use HawkSearch\Connector\Gateway\InstructionException;
use Magento\Framework\Exception\NotFoundException;

class InstructionManager implements InstructionManagerInterface
{
    /**
     * @var InstructionPoolInterface
     */
    private $instructionPool;

    /**
     * CommandExecutor constructor.
     * @param InstructionPoolInterface $instructionPool
     */
    public function __construct(
        InstructionPoolInterface $instructionPool
    ) {
        $this->instructionPool = $instructionPool;
    }

    /**
     * Executes instruction by code
     *
     * @param string $instructionCode
     * @param array $arguments
     * @return ResultInterface|null
     * @throws NotFoundException
     * @throws InstructionException
     * @since 100.1.0
     */
    public function executeByCode($instructionCode, array $arguments = [])
    {
        return $this->instructionPool
            ->get($instructionCode)
            ->execute($arguments);
    }

    /**
     * Executes command
     *
     * @param InstructionInterface $instruction
     * @param array $arguments
     * @return ResultInterface|null
     * @throws InstructionException
     * @since 100.1.0
     */
    public function execute(InstructionInterface $instruction, array $arguments = [])
    {
        return $instruction->execute($arguments);
    }

    /**
     * Retrieves operation
     *
     * @param string $instructionCode
     * @return InstructionInterface
     * @throws NotFoundException
     * @since 100.1.0
     */
    public function get($instructionCode)
    {
        return $this->instructionPool->get($instructionCode);
    }
}
