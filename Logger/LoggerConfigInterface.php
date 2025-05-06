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

use Monolog\Logger;

/**
 * @api
 * @since 2.11
 * @phpstan-import-type Level from \Monolog\Logger as MonologLoggerLevel
 */
interface LoggerConfigInterface
{
    const DEFAULT_LOG_LEVEL = Logger::DEBUG;

    /**
     * Is logger enabled
     *
     * @return  bool
     */
    public function isEnabled();

    /**
     * @return MonologLoggerLevel
     */
    public function getLogLevel();

    /**
     * @param MonologLoggerLevel $level
     * @return $this
     */
    public function setLogLevel(int $level);
}
