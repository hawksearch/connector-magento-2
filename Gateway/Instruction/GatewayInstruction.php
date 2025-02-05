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

namespace HawkSearch\Connector\Gateway\Instruction;

use HawkSearch\Connector\Gateway\ErrorMapper\ErrorMessageMapperInterface;
use HawkSearch\Connector\Gateway\Http\ClientInterface;
use HawkSearch\Connector\Gateway\Http\TransferFactoryInterface;
use HawkSearch\Connector\Gateway\InstructionException;
use HawkSearch\Connector\Gateway\InstructionInterface;
use HawkSearch\Connector\Gateway\Request\BuilderInterface;
use HawkSearch\Connector\Gateway\Response\HandlerInterface;
use HawkSearch\Connector\Gateway\Validator\ResultInterface;
use HawkSearch\Connector\Gateway\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;

class GatewayInstruction implements InstructionInterface
{
    private BuilderInterface $requestBuilder;
    private TransferFactoryInterface $transferFactory;
    private ClientInterface $client;
    private ResultInterfaceFactory $resultFactory;
    private LoggerInterface $logger;
    private ?HandlerInterface $handler;
    private ?ValidatorInterface $validator;
    private ?ErrorMessageMapperInterface $errorMessageMapper;

    public function __construct(
        BuilderInterface $requestBuilder,
        TransferFactoryInterface $transferFactory,
        ClientInterface $client,
        ResultInterfaceFactory $resultFactory,
        LoggerInterface $logger,
        HandlerInterface $handler = null,
        ValidatorInterface $validator = null,
        ErrorMessageMapperInterface $errorMessageMapper = null
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->transferFactory = $transferFactory;
        $this->client = $client;
        $this->resultFactory = $resultFactory;
        $this->logger = $logger;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->errorMessageMapper = $errorMessageMapper;
    }

    /**
     * @return \HawkSearch\Connector\Gateway\Instruction\ResultInterface
     * @throws InstructionException
     */
    public function execute(array $requestSubject)
    {
        // @TODO implement exceptions catching
        $transfer = $this->transferFactory->create(
            $this->requestBuilder->build($requestSubject)
        );

        $response = $this->client->placeRequest($transfer);
        if (isset($this->validator)) {
            $result = $this->validator->validate(
                array_merge($requestSubject, ['response' => $response])
            );
            if (!$result->isValid()) {
                $this->processErrors($result);
            }
        }

        if (isset($this->handler)) {
            $response = $this->handler->handle(
                $requestSubject,
                $response
            );
        }

        return $this->resultFactory->create(['result' => $response]);
    }

    /**
     * Tries to map error messages from validation result and logs processed message.
     * Throws an exception with mapped message or default error.
     *
     * @throws InstructionException
     */
    private function processErrors(ResultInterface $result): void
    {
        $messages = [];
        $errorsSource = array_merge($result->getErrorCodes(), $result->getFailsDescription());
        foreach ($errorsSource as $errorCodeOrMessage) {
            $errorCodeOrMessage = (string)$errorCodeOrMessage;

            if (isset($this->errorMessageMapper)) {
                $mapped = $this->errorMessageMapper->getMessage($errorCodeOrMessage);
                if (!empty($mapped)) {
                    $messages[] = $mapped;
                    $errorCodeOrMessage = $mapped;
                }
            } else {
                $messages[] = $errorCodeOrMessage;
            }
            $this->logger->critical('Error: ' . $errorCodeOrMessage);
        }

        throw new InstructionException(
            !empty($messages)
                ? __(implode(PHP_EOL, $messages))
                : __('API connection error.')
        );
    }
}
