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
 * Class helps to trigger a deprecation error for method parameters.
 * It is also useful for deprecating constructor dependencies.
 *
 * Usage:
 *  - Use public static methods when it requires to deprecate method's parameter
 *
 * Removing deprecation:
 * - Remove method calls when deprecation is removed
 *
 * @internal
 */
class ParameterDeprecation
{
    /**
     * @return void
     */
    public static function triggerDeprecationMessage(
        string $methodName,
        string $parameterName,
        string $since = '',
        string $replacement = '',
        string $extra = ''
    )
    {
        $message = DeprecationUtility::getMessageBuilder()
            ->setMainPart(DeprecatedMessageInterface::TEMPLATE_PARAMETER_MAIN_PART, [
                $parameterName,
                $methodName
            ])
            ->setSincePart(DeprecatedMessageInterface::TEMPLATE_SINCE_PART, [$since])
            ->setReplacementPart(DeprecatedMessageInterface::TEMPLATE_REPLACEMENT_PART, [$replacement])
            ->setExtra($extra)
            ->build();

        DeprecationUtility::getMessageTrigger()->execute($message);
    }
}
