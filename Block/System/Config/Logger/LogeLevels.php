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

namespace HawkSearch\Connector\Block\System\Config\Logger;

use Monolog\Logger;

/**
 * @api
 * @since 2.11
 */
class LogeLevels implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return list<array{label: string, value: int}>
     */
    public function toOptionArray()
    {
        return [
            ['value' => Logger::DEBUG, 'label' => ucfirst(strtolower(Logger::getLevelName(Logger::DEBUG)))],
            ['value' => Logger::INFO, 'label' => ucfirst(strtolower(Logger::getLevelName(Logger::INFO)))]
        ];
    }
}
