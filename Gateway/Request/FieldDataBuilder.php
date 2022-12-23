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

namespace HawkSearch\Connector\Gateway\Request;

use HawkSearch\Connector\Api\Data\HawkSearchFieldInterfaceFactory;
use HawkSearch\Connector\Model\HawkSearchField;

class FieldDataBuilder implements BuilderInterface
{
    /**
     * @var HawkSearchFieldInterfaceFactory
     */
    private $hawkSearchFieldFactory;

    public function __construct(
        HawkSearchFieldInterfaceFactory $hawkSearchFieldFactory
    ) {
        $this->hawkSearchFieldFactory = $hawkSearchFieldFactory;
    }

    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        /** @var HawkSearchField $hawkSearchField */
        $hawkSearchField = $this->hawkSearchFieldFactory->create();
        $hawkSearchField->addData($buildSubject);

        return $hawkSearchField->getData();
    }
}
