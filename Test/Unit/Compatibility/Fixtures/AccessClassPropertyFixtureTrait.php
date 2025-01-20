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

namespace HawkSearch\Connector\Test\Unit\Compatibility\Fixtures;

trait AccessClassPropertyFixtureTrait
{
    public function getPropertyToTest(string $propertyName): mixed
    {
        return $this->$propertyName;
    }

    public function setPropertyToTest(string $propertyName, mixed $value): void
    {
        $this->$propertyName = $value;
    }

    public function unsetPropertyToTest(string $propertyName): void
    {
        unset($this->$propertyName);
    }

    public function issetPropertyToTest(string $propertyName): bool
    {
        return isset($this->$propertyName);
    }
}
