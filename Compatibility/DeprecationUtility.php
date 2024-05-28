<?php
declare(strict_types=1);

namespace HawkSearch\Connector\Compatibility;

use Magento\Framework\App\ObjectManager;

class DeprecationUtility
{
    /**
     * @return DeprecatedMessageBuilderInterface
     */
    public static function getMessageBuilder(): DeprecatedMessageBuilderInterface
    {
        return ObjectManager::getInstance()->get(DeprecatedMessageBuilderInterface::class);
    }

    /**
     * @return DeprecatedMessageTriggerInterface
     */
    public static function getMessageTrigger(): DeprecatedMessageTriggerInterface
    {
        return ObjectManager::getInstance()->get(DeprecatedMessageTriggerInterface::class);
    }
}
