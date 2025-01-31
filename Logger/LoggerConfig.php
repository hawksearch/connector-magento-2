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

use HawkSearch\Connector\Model\Config\Logger as LoggerXmlConfig;

/**
 * @phpstan-import-type Level from \Monolog\Logger as MonologLoggerLevel
 */
class LoggerConfig implements LoggerConfigInterface
{
    /**
     * @var LoggerXmlConfig
     */
    private $loggerXmlConfig;

    /**
     * @var MonologLoggerLevel
     */
    private $logLevel;
    
    public function __construct(
        LoggerXmlConfig $loggerXmlConfig
    ) {
        $this->loggerXmlConfig = $loggerXmlConfig;
    }

    public function isEnabled()
    {
        return $this->loggerXmlConfig->isEnabled();
    }

    public function getLogLevel()
    {
        if (!isset($this->logLevel)) {
            $this->logLevel = $this->loggerXmlConfig->getLogLevel() ?: self::DEFAULT_LOG_LEVEL;
        }

        return $this->logLevel;
    }

    public function setLogLevel(int $level)
    {
        $this->logLevel = $level;
        return $this;
    }
}
