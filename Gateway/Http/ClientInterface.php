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

namespace HawkSearch\Connector\Gateway\Http;

/**
 * Interface ClientInterface
 * Http client interface
 *
 * @phpstan-type HttpResult array{
 *      code: int,
 *      message: string,
 *      response: mixed,
 *  }|array{}
 * @api
 * @todo Remove empty array definition for HttpResult phpstan type
 * @todo Replace HttpResult phpstan iterable type with a new HttpResultInterface type
 */
interface ClientInterface
{
    const RESPONSE_CODE = 'code';
    const RESPONSE_MESSAGE = 'message';
    const RESPONSE_DATA = 'response';

    /**
     * Places request to gateway. Returns result as ENV array
     *
     * @return HttpResult
     */
    public function placeRequest(TransferInterface $transferObject);
}
