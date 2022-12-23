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
declare(strict_types=1);

namespace HawkSearch\Connector\Gateway\Response;

use HawkSearch\Connector\Gateway\Http\ClientInterface;
use HawkSearch\Connector\Api\Data\HawkSearchFieldInterfaceFactory;

class FieldHandler implements HandlerInterface
{
    /**
     * @var HawkSearchFieldInterfaceFactory
     */
    private $fieldFactory;

    public function __construct(
        HawkSearchFieldInterfaceFactory $fieldFactory
    ) {
        $this->fieldFactory = $fieldFactory;
    }

    /**
     * @inheritdoc
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (isset($response[ClientInterface::RESPONSE_DATA])) {
            $response[ClientInterface::RESPONSE_DATA] = $this->fieldFactory->create(
                ['data' => $response[ClientInterface::RESPONSE_DATA]]
            );
        }

        return $response;
    }
}
