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

use Exception;
use Psr\Log\LoggerInterface;

/**
 * Debugger writes information about request, response and possible exception to standard system log.
 */
class Log implements LogInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Log constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function debug(array $data)
    {
        $data = $this->buildDebugMessage($data);
        $this->logger->debug(var_export($data, true));
    }


    /**
     * Build unified debug messages array
     *
     * @param array $debugData
     * @return array
     */
    private function buildDebugMessage(array $debugData)
    {
        foreach ($debugData as $key => $data) {
            if (is_array($data)) {
                $debugData[$key] = $this->buildDebugMessage($data);
            }
        }
        return $debugData;
    }
}
