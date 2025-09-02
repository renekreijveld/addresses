/*
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

/*
 * Mysql uninstall file
 * This SQL script drops the #__addresses database table and cleans the #__categories table
 */

-- Drop #__addresses table
DROP TABLE IF EXISTS `#__addresses`;
-- Cleanup com_addresses categories
DELETE FROM `#__categories` WHERE `extension` = 'com_addresses';