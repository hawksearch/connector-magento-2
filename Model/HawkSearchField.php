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

namespace HawkSearch\Connector\Model;

use HawkSearch\Connector\Api\Data\HawkSearchFieldInterface;
use Magento\Framework\DataObject;

class HawkSearchField extends DataObject implements HawkSearchFieldInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data = [
            "FieldType" => "keyword",
            "Type" => "String",
            "Boost" => 1,
            "FacetHandler" => 0,
            "IsPrimaryKey" => false,
            "IsOutput" => false,
            "IsShingle" => false,
            "IsBestFragment" => false,
            "IsDictionary" => false,
            "IsSort" => false,
            "IsPrefix" => false,
            "IsHidden" => false,
            "IsCompare" => false,
            "SortOrder" => 0,
            "PartialQuery" => "",
            "IsKeywordText" => true,
            "IsQuery" => false,
            "IsQueryText" => false,
            "SkipCustom" => false,
            "StripHtml" => false,
            "MinNGramAnalyzer" => 2,
            "MaxNGramAnalyzer" => 15,
            "CoordinateType" => 0,
            "OmitNorms" => false,
            "ItemMapping" => "",
            "DefaultValue" => "",
            "UseForPrediction" => false,
            "CopyTo" => "",
            "Analyzer" => "",
            "DoNotStore" => true,
            "Tags" => "",
            "Iterations" => [
                1
            ],
            "AnalyzerLanguage" => null,
            "PreviewMapping" => null,
            "OmitTfAndPos" => false
        ]
    ) {
        parent::__construct($data);
    }

    public function getFieldId(): int
    {
        return (int)$this->getData(static::FIELD_ID);
    }

    /**
     * @return $this
     */
    public function setFieldId(string $value)
    {
        return $this->setData(static::FIELD_ID, $value);
    }

    public function getSyncGuid(): string
    {
        return $this->getData(static::SYNC_GUID);
    }

    /**
     * @return $this
     */
    public function setSyncGuid(string $value)
    {
        return $this->setData(static::SYNC_GUID, $value);
    }

    public function getName(): string
    {
        return $this->getData(static::NAME);
    }

    /**
     * @return $this
     */
    public function setName(string $value)
    {
        return $this->setData(static::NAME, $value);
    }

    public function getFieldType(): string
    {
        return $this->getData(static::FIELD_TYPE);
    }

    /**
     * @return $this
     */
    public function setFieldType(string $value)
    {
        return $this->setData(static::FIELD_TYPE, $value);
    }

    public function getLabel(): string
    {
        return $this->getData(static::LABEL);
    }

    /**
     * @return $this
     */
    public function setLabel(string $value)
    {
        return $this->setData(static::LABEL, $value);
    }

    public function getType(): string
    {
        return $this->getData(static::TYPE);
    }

    /**
     * @return $this
     */
    public function setType(string $value)
    {
        return $this->setData(static::TYPE, $value);
    }

    public function getBoost(): int
    {
        return $this->getData(static::BOOST);
    }

    /**
     * @return $this
     */
    public function setBoost(int $value)
    {
        return $this->setData(static::BOOST, $value);
    }

    public function getFacetHandler(): int
    {
        return $this->getData(static::FACET_HANDLER);
    }

    /**
     * @return $this
     */
    public function setFacetHandler(int $value)
    {
        return $this->setData(static::FACET_HANDLER, $value);
    }

    public function getIsPrimaryKey(): bool
    {
        return $this->getData(static::IS_PRIMARY_KEY);
    }

    /**
     * @return $this
     */
    public function setIsPrimaryKey(bool $value)
    {
        return $this->setData(static::IS_PRIMARY_KEY, $value);
    }

    public function getIsOutput(): bool
    {
        return $this->getData(static::IS_OUTPUT);
    }

    /**
     * @return $this
     */
    public function setIsOutput(bool $value)
    {
        return $this->setData(static::IS_OUTPUT, $value);
    }

    public function getIsShingle(): bool
    {
        return $this->getData(static::IS_SHINGLE);
    }

    /**
     * @return $this
     */
    public function setIsShingle(bool $value)
    {
        return $this->setData(static::IS_SHINGLE, $value);
    }

    public function getIsBestFragment(): bool
    {
        return $this->getData(static::IS_BEST_FRAGMENT);
    }

    /**
     * @return $this
     */
    public function setIsBestFragment(bool $value)
    {
        return $this->setData(static::IS_BEST_FRAGMENT, $value);
    }

    public function getIsDictionary(): bool
    {
        return $this->getData(static::IS_DICTIONARY);
    }

    /**
     * @return $this
     */
    public function setIsDictionary(bool $value)
    {
        return $this->setData(static::IS_DICTIONARY, $value);
    }

    public function getIsSort(): bool
    {
        return $this->getData(static::IS_SORT);
    }

    /**
     * @return $this
     */
    public function setIsSort(bool $value)
    {
        return $this->setData(static::IS_SORT, $value);
    }

    public function getIsPrefix(): bool
    {
        return $this->getData(static::IS_PREFIX);
    }

    /**
     * @return $this
     */
    public function setIsPrefix(bool $value)
    {
        return $this->setData(static::IS_PREFIX, $value);
    }

    public function getIsHidden(): bool
    {
        return $this->getData(static::IS_HIDDEN);
    }

    /**
     * @return $this
     */
    public function setIsHidden(bool $value)
    {
        return $this->setData(static::IS_HIDDEN, $value);
    }

    public function getIsCompare(): bool
    {
        return $this->getData(static::IS_COMPARE);
    }

    /**
     * @return $this
     */
    public function setIsCompare(bool $value)
    {
        return $this->setData(static::IS_COMPARE, $value);
    }

    public function getSortOrder(): int
    {
        return $this->getData(static::SORT_ORDER);
    }

    /**
     * @return $this
     */
    public function setSortOrder(int $value)
    {
        return $this->setData(static::SORT_ORDER, $value);
    }

    public function getPartialQuery(): string
    {
        return $this->getData(static::PARTIAL_QUERY);
    }

    /**
     * @return $this
     */
    public function setPartialQuery(string $value)
    {
        return $this->setData(static::PARTIAL_QUERY, $value);
    }

    public function getIsKeywordText(): bool
    {
        return $this->getData(static::IS_KEYWORD_TEXT);
    }

    /**
     * @return $this
     */
    public function setIsKeywordText(bool $value)
    {
        return $this->setData(static::IS_KEYWORD_TEXT, $value);
    }

    public function getIsQuery(): bool
    {
        return $this->getData(static::IS_QUERY);
    }

    /**
     * @return $this
     */
    public function setIsQuery(bool $value)
    {
        return $this->setData(static::IS_QUERY, $value);
    }

    public function getIsQueryText(): bool
    {
        return $this->getData(static::IS_QUERY_TEXT);
    }

    /**
     * @return $this
     */
    public function setIsQueryText(bool $value)
    {
        return $this->setData(static::IS_QUERY_TEXT, $value);
    }

    public function getSkipCustom(): bool
    {
        return $this->getData(static::SKIP_CUSTOM);
    }

    /**
     * @return $this
     */
    public function setSkipCustom(bool $value)
    {
        return $this->setData(static::SKIP_CUSTOM, $value);
    }

    public function getStripHtml(): bool
    {
        return $this->getData(static::STRIP_HTML);
    }

    /**
     * @return $this
     */
    public function setStripHtml(bool $value)
    {
        return $this->setData(static::STRIP_HTML, $value);
    }

    public function getMinNGramAnalyzer(): int
    {
        return $this->getData(static::MIN_N_GRAM_ANALYZER);
    }

    /**
     * @return $this
     */
    public function setMinNGramAnalyzer(int $value)
    {
        return $this->setData(static::MIN_N_GRAM_ANALYZER, $value);
    }

    public function getMaxNGramAnalyzer(): int
    {
        return $this->getData(static::MAX_N_GRAM_ANALYZER);
    }

    /**
     * @return $this
     */
    public function setMaxNGramAnalyzer(int $value)
    {
        return $this->setData(static::MAX_N_GRAM_ANALYZER, $value);
    }

    public function getCoordinateType(): int
    {
        return $this->getData(static::COORDINATE_TYPE);
    }

    /**
     * @return $this
     */
    public function setCoordinateType(int $value)
    {
        return $this->setData(static::COORDINATE_TYPE, $value);
    }

    public function getOmitNorms(): bool
    {
        return $this->getData(static::OMIT_NORMS);
    }

    /**
     * @return $this
     */
    public function setOmitNorms(bool $value)
    {
        return $this->setData(static::OMIT_NORMS, $value);
    }

    public function getItemMapping(): string
    {
        return $this->getData(static::ITEM_MAPPING);
    }

    /**
     * @return $this
     */
    public function setItemMapping(string $value)
    {
        return $this->setData(static::ITEM_MAPPING, $value);
    }

    public function getDefaultValue(): string
    {
        return $this->getData(static::DEFAULT_VALUE);
    }

    /**
     * @return $this
     */
    public function setDefaultValue(string $value)
    {
        return $this->setData(static::DEFAULT_VALUE, $value);
    }

    public function getUseForPrediction(): bool
    {
        return $this->getData(static::USE_FOR_PREDICTION);
    }

    /**
     * @return $this
     */
    public function setUseForPrediction(bool $value)
    {
        return $this->setData(static::USE_FOR_PREDICTION, $value);
    }

    public function getCopyTo(): string
    {
        return $this->getData(static::COPY_TO);
    }

    /**
     * @return $this
     */
    public function setCopyTo(string $value)
    {
        return $this->setData(static::COPY_TO, $value);
    }

    public function getAnalyzer(): string
    {
        return $this->getData(static::ANALYZER);
    }

    /**
     * @return $this
     */
    public function setAnalyzer(string $value)
    {
        return $this->setData(static::ANALYZER, $value);
    }

    public function getDoNotStore(): bool
    {
        return $this->getData(static::DO_NOT_STORE);
    }

    /**
     * @return $this
     */
    public function setDoNotStore(bool $value)
    {
        return $this->setData(static::DO_NOT_STORE, $value);
    }

    public function getTags(): string
    {
        return $this->getData(static::TAGS);
    }

    /**
     * @return $this
     */
    public function setTags(string $value)
    {
        return $this->setData(static::TAGS, $value);
    }

    /**
     * @return int[]
     */
    public function getIterations(): array
    {
        return $this->getData(static::ITERATIONS);
    }

    public function setIterations(array $value)
    {
        return $this->setData(static::ITERATIONS, $value);
    }

    /**
     * @return string
     */
    public function getAnalyzerLanguage()
    {
        return $this->getData(static::ANALYZER_LANGUAGE);
    }

    /**
     * @return $this
     */
    public function setAnalyzerLanguage(string $value)
    {
        return $this->setData(static::ANALYZER_LANGUAGE, $value);
    }

    /**
     * @return string
     */
    public function getPreviewMapping()
    {
        return $this->getData(static::PREVIEW_MAPPING);
    }

    /**
     * @return $this
     */
    public function setPreviewMapping(string $value)
    {
        return $this->setData(static::PREVIEW_MAPPING, $value);
    }

    public function getOmitTfAndPos(): bool
    {
        return $this->getData(static::OMIT_TF_ADN_POS);
    }

    /**
     * @return $this
     */
    public function setOmitTfAndPos(bool $value)
    {
        return $this->setData(static::OMIT_TF_ADN_POS, $value);
    }

    public function getCreateDate(): string
    {
        return $this->getData(static::CREATE_DATE);
    }

    /**
     * @return $this
     */
    public function setCreateDate(string $value)
    {
        return $this->setData(static::CREATE_DATE, $value);
    }

    public function getModifyDate(): string
    {
        return $this->getData(static::MODIFY_DATE);
    }

    /**
     * @return $this
     */
    public function setModifyDate(string $value)
    {
        return $this->setData(static::MODIFY_DATE, $value);
    }
}
