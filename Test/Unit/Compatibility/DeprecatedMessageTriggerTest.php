<?php
/**
 * Copyright (c) 2025 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Test\Unit\Compatibility;

use HawkSearch\Connector\Compatibility\DeprecatedMessageTrigger;
use PHPUnit\Framework\TestCase;

class DeprecatedMessageTriggerTest extends TestCase
{
    private DeprecatedMessageTrigger $model;

    protected function setUp(): void
    {
        $this->model = new DeprecatedMessageTrigger();
    }

    public function testExecuteWithDeprecatedMessageNotice(): void
    {
        set_error_handler(fn() => false);
        $e = error_reporting(0);
        trigger_error('', E_USER_NOTICE);

        $this->model->execute('Deprecated message');

        error_reporting($e);
        restore_error_handler();

        $lastError = error_get_last();
        unset($lastError['file'], $lastError['line']);

        $xError = [
            'type' => E_USER_DEPRECATED,
            'message' => 'Deprecated message',
        ];

        $this->assertSame($xError, $lastError);
    }

    public function testExecuteWithoutNotice(): void
    {
        set_error_handler(fn() => false);
        $e = error_reporting(0);
        trigger_error('', E_USER_NOTICE);

        $this->model->execute('');

        error_reporting($e);
        restore_error_handler();

        $lastError = error_get_last();
        unset($lastError['file'], $lastError['line']);

        $xError = [
            'type' => E_USER_NOTICE,
            'message' => '',
        ];

        $this->assertSame($xError, $lastError);
    }
}
