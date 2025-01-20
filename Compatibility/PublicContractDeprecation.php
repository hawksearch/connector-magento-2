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
 * Class helps to trigger a deprecation error for public contracts (interfaces) and classes.
 *
 * Usage:
 *  - Use public static methods in the file containing a deprecating functionality
 *
 * Removing deprecation:
 * - Remove the whole file with a deprecated interface or class
 *
 * Example:
 * <?php
 *
 * PublicContractDeprecation::triggerDeprecationMessage(
 *     ExampleInterface::class,
 *     '1.1.0',
 *     '',
 *     'Example logic will be removed'
 * );
 * interface ExampleInterface
 * {
 * }
 *
 * @internal
 */
class PublicContractDeprecation
{
    /**
     * @return void
     */
    public static function triggerClassDeprecationMessage(
        string $contractName,
        string $since = '',
        string $replacement = '',
        string $extra = ''
    )
    {
        $message = DeprecationUtility::getMessageBuilder()
            ->setMainPart(DeprecatedMessageInterface::TEMPLATE_CLASS_MAIN_PART, [$contractName])
            ->setSincePart(DeprecatedMessageInterface::TEMPLATE_SINCE_PART, [$since])
            ->setReplacementPart(DeprecatedMessageInterface::TEMPLATE_REPLACEMENT_PART, [$replacement])
            ->setExtra($extra)
            ->build();

        DeprecationUtility::getMessageTrigger()->execute($message);
    }

    /**
     * @return void
     */
    public static function triggerInterfaceDeprecationMessage(
        string $contractName,
        string $since = '',
        string $replacement = '',
        string $extra = ''
    )
    {
        $message = DeprecationUtility::getMessageBuilder()
            ->setMainPart(DeprecatedMessageInterface::TEMPLATE_INTERFACE_MAIN_PART, [$contractName])
            ->setSincePart(DeprecatedMessageInterface::TEMPLATE_SINCE_PART, [$since])
            ->setReplacementPart(DeprecatedMessageInterface::TEMPLATE_REPLACEMENT_PART, [$replacement])
            ->setExtra($extra)
            ->build();

        DeprecationUtility::getMessageTrigger()->execute($message);
    }
}
