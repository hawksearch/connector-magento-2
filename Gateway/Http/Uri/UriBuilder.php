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

namespace HawkSearch\Connector\Gateway\Http\Uri;

use HawkSearch\Connector\Helper\Url as UrlUtility;

class UriBuilder implements UriBuilderInterface
{
    /**
     * @var UrlUtility
     */
    private $urlUtility;

    /**
     * UriBuilder constructor.
     * @param UrlUtility $urlUtility
     */
    public function __construct(
        UrlUtility $urlUtility
    ) {
        $this->urlUtility = $urlUtility;
    }

    /**
     * @inheritDoc
     */
    public function build(string $url, string $path): string
    {
        return $this->urlUtility->getUriWithPath($url, $path)->__toString();
    }
}