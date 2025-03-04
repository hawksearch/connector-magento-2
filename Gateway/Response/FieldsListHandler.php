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

namespace HawkSearch\Connector\Gateway\Response;

use HawkSearch\Connector\Api\Data\HawkSearchFieldInterfaceFactory;
use HawkSearch\Connector\Gateway\Http\ClientInterface;

class FieldsListHandler implements HandlerInterface
{
    /**
     * @var HawkSearchFieldInterfaceFactory
     */
    private HawkSearchFieldInterfaceFactory $fieldFactory;

    /**
     * @param HawkSearchFieldInterfaceFactory $fieldFactory
     */
    public function __construct(
        HawkSearchFieldInterfaceFactory $fieldFactory
    ) {
        $this->fieldFactory = $fieldFactory;
    }

    /**
     * @return array<string, mixed>
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (isset($response[ClientInterface::RESPONSE_DATA]) && is_array($response[ClientInterface::RESPONSE_DATA])) {
            $handledData = [];
            foreach ($response[ClientInterface::RESPONSE_DATA] as $field) {
                $handledData[] = $this->fieldFactory->create(['data' => $field]);
            }
            $response[ClientInterface::RESPONSE_DATA] = $handledData;
        }

        return $response;
    }
}
