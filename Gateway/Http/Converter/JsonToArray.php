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

namespace HawkSearch\Connector\Gateway\Http\Converter;

use HawkSearch\Connector\Gateway\Http\ConverterInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class JsonToArray
 */
class JsonToArray implements ConverterInterface
{
    /**
     * @var Json
     */
    private $json;

    /**
     * JsonToArray constructor.
     * @param Json $json
     */
    public function __construct(
        Json $json
    ) {
        $this->json = $json;
    }

    /**
     * @inheritDoc
     */
    public function convert($response)
    {
        if (!is_string($response)) {
            throw new \InvalidArgumentException(__('The response type is incorrect. Verify the type and try again.'));
        }

        if ($response === '') {
            $response = '{}';
        }

        return $this->json->unserialize($response);
    }
}
