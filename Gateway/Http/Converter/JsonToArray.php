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

namespace HawkSearch\Connector\Gateway\Http\Converter;

use HawkSearch\Connector\Gateway\Http\ConverterInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * @api
 * @since 2.11
 */
class JsonToArray implements ConverterInterface
{
    private Json $json;

    public function __construct(
        Json $json
    ) {
        $this->json = $json;
    }

    /**
     * @param string $data
     * @return mixed[]
     */
    public function convert(mixed $data)
    {
        if (!is_string($data)) {
            throw new \InvalidArgumentException(__('$data Parameter is not a string.')->render());
        }

        if ($data === '') {
            $data = '{}';
        }

        return (array)$this->json->unserialize($data);
    }
}
