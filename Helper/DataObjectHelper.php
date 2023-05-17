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

namespace HawkSearch\Connector\Helper;

/**
 * Additional service for converting data keys and populating objects
 * @see \Magento\Framework\Api\DataObjectHelper
 */
class DataObjectHelper
{
    /**
     * Recursively converts associative array's key names from camelCase to snake_case.
     * @param array $data
     * @return array
     */
    public function convertArrayToSnakeCase(array $data)
    {
        foreach ($data as $name => $value) {
            $snakeCaseName = is_string($name) ? $this->camelCaseToSnakeCase($name) : $name;
            if (is_array($value)) {
                $value = $this->convertArrayToSnakeCase($value);
            }
            unset($data[$name]);
            $data[$snakeCaseName] = $value;
        }
        return $data;
    }

    /**
     * Convert a CamelCase string read from method into field key in snake_case
     * @see https://github.com/magento/magento2/issues/35457
     *
     * @param string $value
     * @return string
     */
    public function camelCaseToSnakeCase(string $value)
    {
        [$pattern, $replacement] = $this->getPatternAndReplacement();

        $filtered = preg_replace($pattern, $replacement, $value);

        return strtolower($filtered);
    }

    /**
     * @return string[][]
     */
    protected function getPatternAndReplacement(): array
    {
        return [
            [ // pattern
                '#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#',
                '#(?<=(?:[a-z0-9]))([A-Z])#',
            ],
            [ // replacement
                '\1_\2',
                '_\1',
            ],
        ];
    }
}
