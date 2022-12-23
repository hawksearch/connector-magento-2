<?php
/**
 * Copyright (c) 2022 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

namespace HawkSearch\Connector\Gateway\Logger;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;

/**
 * Factory produces debugger based on runtime configuration.
 *
 * phpcs:disable MEQP2.Classes.ObjectManager
 */
class LoggerFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * DebuggerFactory constructor.
     *
     * @param objectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Create debugger instance
     *
     * @return LogInterface
     * @throws NoSuchEntityException
     */
    public function create()
    {
        //@TODO Create BlackHole logger instance if debugging is disabled in config

        return $this->objectManager->get(Log::class);
    }
}
