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

namespace HawkSearch\Connector\Gateway\Validator;

use HawkSearch\Connector\Gateway\Helper\HttpResponseReader;
use HawkSearch\Connector\Gateway\Helper\SubjectReader;

class HttpCodeValidator extends AbstractValidator
{
    private HttpResponseReader $httpResponseReader;
    private SubjectReader $subjectReader;

    public function __construct(
        ResultInterfaceFactory $resultFactory,
        HttpResponseReader $httpResponseReader,
        SubjectReader $subjectReader
    ) {
        parent::__construct($resultFactory);
        $this->httpResponseReader = $httpResponseReader;
        $this->subjectReader = $subjectReader;
    }

    public function validate(array $validationSubject): ResultInterface
    {
        $response = $this->subjectReader->readResponse($validationSubject);
        $responseCode = $this->httpResponseReader->readResponseCode($response);
        $responseMessage = $this->httpResponseReader->readResponseMessage($response);

        if ($responseCode < 200 || ($responseCode > 299 && $responseCode < 400) || $responseCode > 400) {
            return $this->createResult(
                false,
                [
                    'Error Code: ' . $responseCode . '.'
                    . ($responseMessage ? " API Client Error Message: " . $responseMessage : '')
                ]
            );
        }

        return $this->createResult(true);
    }
}
