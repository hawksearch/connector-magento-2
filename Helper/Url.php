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

namespace HawkSearch\Connector\Helper;

use GuzzleHttp\Psr7\UriFactory;
use Psr\Http\Message\UriInterface;

/**
 * @api
 * @since 2.11
 */
class Url
{
    private UriFactory $uriFactory;

    public function __construct(
        UriFactory $uriFactory
    ) {
        $this->uriFactory = $uriFactory;
    }

    /**
     * @return UriInterface
     */
    public function getUriWithPath(string $url, string $path)
    {
        $uri = $this->getUriInstance($url);
        $isTrailingSlash = $this->isTrailingSlashInPath($path);

        $path = $this->implodeUriPath($this->explodeUriPath($path));
        $path = $isTrailingSlash ? $this->addTrailingSlash($path) : $path;

        return $uri->withPath($path);
    }

    /**
     * @param string $url
     * @param array<mixed> $query
     * @return UriInterface
     */
    public function getUriWithQuery(string $url, array $query)
    {
        $uri = $this->getUriInstance($url);

        return $uri->withQuery(http_build_query($query));
    }

    /**
     * Concatenate extra parts to the start or the end of the URI path
     *
     * @param UriInterface $uri
     * @param string[] $addToPath
     * @param bool $fromStart
     * @return UriInterface
     */
    public function addToUriPath(UriInterface $uri, array $addToPath, bool $fromStart = true)
    {
        $isTrailingSlash = $this->isTrailingSlashInPath($uri->getPath());
        $isTrailingSlash = $isTrailingSlash || (!$fromStart && end($addToPath) == '/');
        reset($addToPath);

        $addToPath = $this->filterPathParts($addToPath);
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

        $path = $this->implodeUriPath($pathParts);
        $path = $isTrailingSlash ? $this->addTrailingSlash($path) : $path;

        return $uri->withPath($path);
    }

    /**
     * @param UriInterface $uri
     * @param string[] $removeParts
     * @return UriInterface
     */
    public function removeFromUriPath(UriInterface $uri, array $removeParts)
    {
        $pathParts = $this->explodeUriPath($uri->getPath());
        $removeParts = $this->filterPathParts($removeParts);
        $isTrailingSlash = $this->isTrailingSlashInPath($uri->getPath());

        $pathParts = array_values(array_filter($pathParts, function ($value) use ($removeParts) {
            return !in_array($value, $removeParts);
        }));

        $path = $this->implodeUriPath($pathParts);
        $path = $isTrailingSlash ? $this->addTrailingSlash($path) : $path;

        return $uri->withPath($path);
    }

    /**
     * Explodes path parts from an uri string
     *
     * @return string[]
     */
    protected function explodeUriPath(string $path)
    {
        $pathParts = explode('/', $path);

        return $this->filterPathParts($pathParts);
    }

    /**
     * Clean empty parts in the path
     *
     * @param string[] $pathParts
     * @return string[]
     */
    protected function filterPathParts(array $pathParts)
    {
        return array_values(array_filter($pathParts, function ($value) {
            return !in_array($value, ['/', '']);
        }));
    }

    /**
     * Add trailing slash to URI path
     *
     * @return string
     */
    protected function addTrailingSlash(string $path)
    {
        return rtrim($path, '/') . '/';
    }

    /**
     * Check if URI path has a trailing slash
     *
     * @return bool
     */
    protected function isTrailingSlashInPath(string $path)
    {
        return substr($path, -1) === '/';
    }

    /**
     * Implode path parts into a well-formed uri path
     *
     * @param string[] $pathParts
     * @return string
     */
    protected function implodeUriPath(array $pathParts)
    {
        return '/' . ltrim(implode('/', $pathParts), '/');
    }

    /**
     * @return UriInterface
     */
    public function getUriInstance(string $uri)
    {
        return $this->uriFactory->create(['uri' => $uri]);
    }
}
