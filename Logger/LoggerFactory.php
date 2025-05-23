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

namespace HawkSearch\Connector\Logger;

use HawkSearch\Connector\Logger\Handler\Debug as DebugHandler;
use Magento\Framework\ObjectManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Factory produces logger based on configuration.
 *
 * @api
 * @since 2.11
 */
class LoggerFactory implements LoggerFactoryInterface
{
    private ObjectManagerInterface $objectManager;
    private LoggerConfigInterface $loggerConfig;
    private string $instanceName;

    public function __construct(
        ObjectManagerInterface $objectManager,
        LoggerConfigInterface $loggerConfig,
        string $instanceName = '\\Psr\\Log\\LoggerInterface'
    ) {
        $this->objectManager = $objectManager;
        $this->loggerConfig = $loggerConfig;
        $this->instanceName = $instanceName;
    }

    /**
     * @return LoggerInterface
     * @throws \InvalidArgumentException if $instanceName has wrong type
     */
    public function create()
    {
        if (!$this->loggerConfig->isEnabled()) {
            return $this->objectManager->get(NullLogger::class);
        }

        /** @var MonologLogger $logger */
        $logger = $this->objectManager->get($this->instanceName);

        if (!($logger instanceof MonologLogger)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s constructor expects the $instanceName to implement %s; received %s',
                    self::class,
                    MonologLogger::class,
                    get_class($logger)
                )
            );
        }

        $this->adjustHandlersLevel($logger);

        return $logger;
    }

    /**
     * Set minimum logging level to debug handler according to configured level
     *
     * @return void
     */
    protected function adjustHandlersLevel(MonologLogger $logger)
    {
        /** @var AbstractProcessingHandler $handler */
        foreach ($logger->getHandlers() as $handler) {
            if (!$handler instanceof AbstractProcessingHandler) {
                continue;
            }

            if ($handler instanceof DebugHandler) {
                $handler->setLevel($this->loggerConfig->getLogLevel());
            }
        }
    }
}
