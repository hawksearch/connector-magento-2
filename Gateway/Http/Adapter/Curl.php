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

namespace HawkSearch\Connector\Gateway\Http\Adapter;

use Zend_Http_Client_Exception;

class Curl extends \Magento\Framework\HTTP\Adapter\Curl
{
    /**
     * @inheritDoc
     * @throws Zend_Http_Client_Exception
     */
    public function read()
    {
        $response = curl_exec($this->_getResource());

        $error_code = $this->getErrno();
        $error = $this->getError();

        if ($response === false && $error_code) {
            throw new Zend_Http_Client_Exception("Error in cURL request: " . $error, $error_code);
        }

        // Remove 100 and 101 responses headers
        while (\Zend_Http_Response::extractCode($response) == 100
            || \Zend_Http_Response::extractCode($response) == 101
        ) {
            $response = preg_split('/^\r?$/m', $response, 2);
            $response = trim($response[1]);
        }

        // CUrl will handle chunked data but leave the header.
        return preg_replace('/Transfer-Encoding:\s+chunked\r?\n/i', '', $response);
    }
}
