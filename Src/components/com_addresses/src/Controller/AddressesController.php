<?php
/**
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Site\Controller;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Addresses list controller
 */
class AddressesController extends BaseController
{
	/**
	 * Proxy for getModel.
	 * @since    1.6
	 *
	 * @param string $name
	 * @param string $prefix
	 *
	 * @return mixed
	 */
	public function &getModel($name = 'Address', $prefix = 'Administrator')
	{
		return parent::getModel($name, $prefix, ['ignore_request' => true]);
	}
}
