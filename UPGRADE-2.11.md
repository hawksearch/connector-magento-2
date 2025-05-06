# UPGRADE FROM 2.10 to 2.11

- Upgrade your Magento/Adobe Commerce store to at least version 2.4.4 and PHP to version 8.1 or higher
- Deprecate overriding any HawkSearch's public/protected methods without declaring return type explicitly. 
  Update any public and protected method's return types to follow the HawkSearch parent methods' return types. 
  Use `./vendor/bin/magento-patch-type-declarations` utility to show all possible deprecation messages.
