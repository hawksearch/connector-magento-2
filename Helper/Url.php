<?php
/**
 * Copyright (c) 2021 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Helper;

use GuzzleHttp\Psr7\UriFactory;
use Psr\Http\Message\UriInterface;

class Url
{
    /**
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * Url constructor.
     * @param UriFactory $uriFactory
     */
    public function __construct(
        UriFactory $uriFactory
    ) {
        $this->uriFactory = $uriFactory;
    }

    /**
     * @param string $url
     * @param string $path
     * @return UriInterface
     */
    public function getUriWithPath(string $url, string $path)
    {
        $uri = $this->getUriInstance($url);

        return $uri->withPath($this->implodeUriPath($this->explodeUriPath($path)));
    }

    /**
     * @param string $url
     * @param array $query
     * @return UriInterface
     */
    public function getUriWithQuery(string $url, array $query)
    {
        $uri = $this->getUriInstance($url);

        return $uri->withQuery(http_build_query($query));
    }

    /**
     * @param UriInterface $uri
     * @param array $addToPath
     * @param bool $fromStart
     * @return UriInterface
     */
    public function addToUriPath(UriInterface $uri, array $addToPath, $fromStart = true)
    {
        $pathParts = $this->explodeUriPath($uri->getPath());

        if ($fromStart) {
            $addToPath = array_reverse($addToPath);
            foreach ($addToPath as $addedPart) {
                if (isset($pathParts[0]) && $pathParts[0] == $addedPart) {
                    continue;
                }
                array_unshift($pathParts, $addedPart);
            }
        } else {
            $pathParts = array_merge($pathParts, $addToPath);
        }

        return $uri->withPath($this->implodeUriPath($pathParts));
    }

    /**
     * @param UriInterface $uri
     * @param array $removeParts
     * @return UriInterface
     */
    public function removeFromUriPath(UriInterface $uri, array $removeParts)
    {
        $pathParts = $this->explodeUriPath($uri->getPath());

        $pathParts = array_values(array_filter($pathParts, function ($value) use ($removeParts) {
            return !in_array($value, $removeParts);
        }));

        return $uri->withPath($this->implodeUriPath($pathParts));
    }

    /**
     * Explodes path parts from an uri string
     * @param string $path
     * @return array
     */
    protected function explodeUriPath(string $path)
    {
        $pathParts = explode('/', $path);

        return array_values(array_filter($pathParts, function ($value) {
            return !in_array($value, ['/', '']);
        }));
    }

    /**
     * Implode path parts into a well-formed uri path
     * @param array $pathParts
     * @return string
     */
    protected function implodeUriPath(array $pathParts)
    {
        return '/' . ltrim(implode('/', $pathParts), '/');
    }

    /**
     * @param string $uri
     * @return UriInterface
     */
    public function getUriInstance(string $uri)
    {
        return $this->uriFactory->create(['uri' => $uri]);
    }
}
