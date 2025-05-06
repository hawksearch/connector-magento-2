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

namespace HawkSearch\Connector\Gateway\Request;

use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

/**
 * @api
 * @since 2.11
 *
 * @phpstan-import-type RequestSubject from \HawkSearch\Connector\Gateway\InstructionInterface
 * @todo replace RequestSubject pseudo type by RequestInterface
 *
 * @template TKey of array-key
 * @template TValue of BuilderInterface
 */
class BuilderComposite implements BuilderInterface
{
    /**
     * @var TMap<TKey, TValue>
     */
    private TMap $builders;

    /**
     * @param TMapFactory $tmapFactory
     * @param array<TKey, class-string<TValue>> $builders
     */
    public function __construct(
        TMapFactory $tmapFactory,
        array $builders = []
    ) {
        $this->builders = $tmapFactory->create(
            [
                'array' => $builders,
                'type' => BuilderInterface::class
            ]
        );
    }

    /**
     * @return RequestSubject
     */
    public function build(array $buildSubject)
    {
        $result = [];
        foreach ($this->builders as $builder) {
            // @TODO implement exceptions catching
            $result = $this->merge($result, $builder->build($buildSubject));
        }

        return $result;
    }

    /**
     * Merge function for builders
     *
     * @param RequestSubject $result
     * @param RequestSubject $builder
     * @return RequestSubject
     */
    protected function merge(array $result, array $builder)
    {
        return array_replace_recursive($result, $builder);
    }
}
