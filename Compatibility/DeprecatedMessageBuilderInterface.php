<?php
/**
 * Copyright (c) 2024 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Compatibility;

/**
 * @internal
 */
interface DeprecatedMessageBuilderInterface
{
    /**#@+
     * Deprecated message parts
     */
    const PART_SINCE = 'since';
    const PART_MAIN = 'main';
    const PART_REPLACEMENT = 'replacement';
    const PART_EXTRA = 'extra';
    /**#@-*/

    /**
     * @param string $format
     * @param string[] $values
     * @return self
     */
    public function setSincePart(string $format, array $values = []): self;

    /**
     * @param string $format
     * @param string[] $values
     * @return self
     */
    public function setMainPart(string $format, array $values = []): self;

    /**
     * @param string $format
     * @param string[] $values
     * @return self
     */
    public function setReplacementPart(string $format, array $values = []): self;

    /**
     * @param string $format
     * @param string[] $values
     * @return self
     */
    public function setExtra(string $format, array $values = []): self;

    /**
     * Build a final message string
     *
     * @return string
     */
    public function build(): string;
}
