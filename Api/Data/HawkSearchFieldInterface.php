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

namespace HawkSearch\Connector\Api\Data;

/**
 * Interface HawkSearchFieldInterface
 *
 * @api
 */
interface HawkSearchFieldInterface
{
    const FIELD_ID = 'FieldId';
    const SYNC_GUID = 'SyncGuid';
    const NAME = 'Name';
    const FIELD_TYPE = 'FieldType';
    const LABEL = 'Label';
    const TYPE = 'Type';
    const BOOST = 'Boost';
    const FACET_HANDLER = 'FacetHandler';
    const IS_PRIMARY_KEY = 'IsPrimaryKey';
    const IS_OUTPUT = 'IsOutput';
    const IS_SHINGLE = 'IsShingle';
    const IS_BEST_FRAGMENT = 'IsBestFragment';
    const IS_DICTIONARY = 'IsDictionary';
    const IS_SORT = 'IsSort';
    const IS_PREFIX = 'IsPrefix';
    const IS_HIDDEN = 'IsHidden';
    const IS_COMPARE = 'IsCompare';
    const SORT_ORDER = 'SortOrder';
    const PARTIAL_QUERY = 'PartialQuery';
    const IS_KEYWORD_TEXT = 'IsKeywordText';
    const IS_QUERY = 'IsQuery';
    const IS_QUERY_TEXT = 'IsQueryText';
    const SKIP_CUSTOM = 'SkipCustom';
    const STRIP_HTML = 'StripHtml';
    const MIN_N_GRAM_ANALYZER = 'MinNGramAnalyzer';
    const MAX_N_GRAM_ANALYZER = 'MaxNGramAnalyzer';
    const COORDINATE_TYPE = 'CoordinateType';
    const OMIT_NORMS = 'OmitNorms';
    const ITEM_MAPPING = 'ItemMapping';
    const DEFAULT_VALUE = 'DefaultValue';
    const USE_FOR_PREDICTION = 'UseForPrediction';
    const COPY_TO = 'CopyTo';
    const ANALYZER = 'Analyzer';
    const DO_NOT_STORE = 'DoNotStore';
    const TAGS = 'Tags';
    const ITERATIONS = 'Iterations';
    const ANALYZER_LANGUAGE = 'AnalyzerLanguage';
    const PREVIEW_MAPPING = 'PreviewMapping';
    const OMIT_TF_ADN_POS = 'OmitTfAndPos';
    const CREATE_DATE = 'CreateDate';
    const MODIFY_DATE = 'ModifyDate';

    public function getFieldId(): int;

    /**
     * @return $this
     */
    public function setFieldId(string $value);

    public function getSyncGuid(): string;

    /**
     * @return $this
     */
    public function setSyncGuid(string $value);

    public function getName(): string;

    /**
     * @return $this
     */
    public function setName(string $value);

    public function getFieldType(): string;

    /**
     * @return $this
     */
    public function setFieldType(string $value);

    public function getLabel(): string;

    /**
     * @return $this
     */
    public function setLabel(string $value);

    public function getType(): string;

    /**
     * @return $this
     */
    public function setType(string $value);

    public function getBoost(): int;

    /**
     * @return $this
     */
    public function setBoost(int $value);

    public function getFacetHandler(): int;

    /**
     * @return $this
     */
    public function setFacetHandler(int $value);

    public function getIsPrimaryKey(): bool;

    /**
     * @return $this
     */
    public function setIsPrimaryKey(bool $value);

    public function getIsOutput(): bool;

    /**
     * @return $this
     */
    public function setIsOutput(bool $value);

    public function getIsShingle(): bool;

    /**
     * @return $this
     */
    public function setIsShingle(bool $value);

    public function getIsBestFragment(): bool;

    /**
     * @return $this
     */
    public function setIsBestFragment(bool $value);

    public function getIsDictionary(): bool;

    /**
     * @return $this
     */
    public function setIsDictionary(bool $value);

    public function getIsSort(): bool;

    /**
     * @return $this
     */
    public function setIsSort(bool $value);

    public function getIsPrefix(): bool;

    /**
     * @return $this
     */
    public function setIsPrefix(bool $value);

    public function getIsHidden(): bool;

    /**
     * @return $this
     */
    public function setIsHidden(bool $value);

    public function getIsCompare(): bool;

    /**
     * @return $this
     */
    public function setIsCompare(bool $value);

    public function getSortOrder(): int;

    /**
     * @return $this
     */
    public function setSortOrder(int $value);

    public function getPartialQuery(): string;

    /**
     * @return $this
     */
    public function setPartialQuery(string $value);

    public function getIsKeywordText(): bool;

    /**
     * @return $this
     */
    public function setIsKeywordText(bool $value);

    public function getIsQuery(): bool;

    /**
     * @return $this
     */
    public function setIsQuery(bool $value);

    public function getIsQueryText(): bool;

    /**
     * @return $this
     */
    public function setIsQueryText(bool $value);

    public function getSkipCustom(): bool;

    /**
     * @return $this
     */
    public function setSkipCustom(bool $value);

    public function getStripHtml(): bool;

    /**
     * @return $this
     */
    public function setStripHtml(bool $value);

    public function getMinNGramAnalyzer(): int;

    /**
     * @return $this
     */
    public function setMinNGramAnalyzer(int $value);

    public function getMaxNGramAnalyzer(): int;

    /**
     * @return $this
     */
    public function setMaxNGramAnalyzer(int $value);

    public function getCoordinateType(): int;

    /**
     * @return $this
     */
    public function setCoordinateType(int $value);

    public function getOmitNorms(): bool;

    /**
     * @return $this
     */
    public function setOmitNorms(bool $value);

    public function getItemMapping(): string;

    /**
     * @return $this
     */
    public function setItemMapping(string $value);

    public function getDefaultValue(): string;

    /**
     * @return $this
     */
    public function setDefaultValue(string $value);

    public function getUseForPrediction(): bool;

    /**
     * @return $this
     */
    public function setUseForPrediction(bool $value);

    public function getCopyTo(): string;

    /**
     * @return $this
     */
    public function setCopyTo(string $value);

    public function getAnalyzer(): string;

    /**
     * @return $this
     */
    public function setAnalyzer(string $value);

    public function getDoNotStore(): bool;

    /**
     * @return $this
     */
    public function setDoNotStore(bool $value);

    public function getTags(): string;

    /**
     * @return $this
     */
    public function setTags(string $value);

    public function getIterations(): array;

    /**
     * @param list<int> $value
     * @return $this
     */
    public function setIterations(array $value);

    /**
     * @return string
     */
    public function getAnalyzerLanguage();

    /**
     * @return $this
     */
    public function setAnalyzerLanguage(string $value);

    /**
     * @return string
     */
    public function getPreviewMapping();

    /**
     * @return $this
     */
    public function setPreviewMapping(string $value);

    public function getOmitTfAndPos(): bool;

    /**
     * @return $this
     */
    public function setOmitTfAndPos(bool $value);

    public function getCreateDate(): string;

    /**
     * @return $this
     */
    public function setCreateDate(string $value);

    public function getModifyDate(): string;

    /**
     * @return $this
     */
    public function setModifyDate(string $value);
}
