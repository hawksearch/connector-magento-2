<?php
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
     * @param string $methodName Fully qualified method name
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
