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
use HawkSearch\Connector\Gateway\InstructionException;
use Magento\Framework\Exception\NotFoundException;

/**
 * @api
 * @since 2.11
 */
class InstructionManager implements InstructionManagerInterface
{
    /**
     * @var InstructionPoolInterface<string, InstructionInterface>
     */
    private $instructionPool;

    /**
     * CommandExecutor constructor.
     * @param InstructionPoolInterface<string, InstructionInterface> $instructionPool
     */
    public function __construct(
        InstructionPoolInterface $instructionPool
    ) {
        $this->instructionPool = $instructionPool;
    }

    public function executeByCode(string $instructionCode, array $arguments = [])
    {
        return $this->instructionPool
            ->get($instructionCode)
            ->execute($arguments);
    }

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
     * @todo remove unused method
     */
    public function get(string $instructionCode)
    {
        return $this->instructionPool->get($instructionCode);
    }
}
