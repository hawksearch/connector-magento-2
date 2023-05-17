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

use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

class HandlerChain implements HandlerInterface
{
    /**
     * @var HandlerInterface[] | TMap
     */
    private $handlers;

    /**
     * @param TMapFactory $tmapFactory
     * @param array $handlers
     */
    public function __construct(
        TMapFactory $tmapFactory,
        array $handlers = []
    ) {
        $this->handlers = $tmapFactory->create(
            [
                'array' => $handlers,
                'type' => HandlerInterface::class
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function handle(array $handlingSubject, array $response)
    {
        foreach ($this->handlers as $handler) {
            $response = $handler->handle($handlingSubject, $response);
        }

        return $response;
    }
}
