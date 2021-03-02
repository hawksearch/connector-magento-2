<?php
/**
 * Copyright (c) 2021 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

use HawkSearch\Connector\Gateway\InstructionException;
use HawkSearch\Connector\Gateway\InstructionInterface;
use Magento\Framework\Exception\NotFoundException;

interface InstructionManagerInterface
{
    /**
     * Executes instruction by code
     *
     * @param string $instructionCode
     * @param array $arguments
     * @return ResultInterface|null
     * @throws NotFoundException
     * @throws InstructionException
     */
    public function executeByCode($instructionCode, array $arguments = []);

    /**
     * Executes instruction
     *
     * @param InstructionInterface $instruction
     * @param array $arguments
     * @return ResultInterface|null
     * @throws InstructionException
     */
    public function execute(InstructionInterface $instruction, array $arguments = []);
}
