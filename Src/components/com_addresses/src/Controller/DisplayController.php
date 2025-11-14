<?php
/**
 * @package     Com_addresses
 * @version     1.3.3
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Base controller class
 *
 * @since  3.1
 */
class DisplayController extends BaseController
{
	/**
	 * Method to display a view
	 *
	 * @param boolean $cachable If true, the view output will be cached
	 * @param mixed|boolean $urlparams An array of safe URL parameters and their
	 *                                     variable types, for valid values see {@link \JFilterInput::clean()}.
	 *
	 * @return JControllerLegacy|BaseController
	 *
	 * @throws Exception
	 * @since   3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$user = Factory::getApplication()->getIdentity();

		// Set the default view name and format from the Request
		$vName = $this->input->get('view', 'Addresses');
		$this->input->set('view', $vName);

		if ($user->get('id') || ($this->input->getMethod() === 'POST' && $vName === 'Addresses')) {
			$cachable = false;
		}

		$safeurlparams = array(
			'id' => 'ARRAY',
			'type' => 'ARRAY',
			'limit' => 'UINT',
			'limitstart' => 'UINT',
			'filter_order' => 'CMD',
			'filter_order_Dir' => 'CMD',
			'lang' => 'CMD'
		);

		Factory::getLanguage()->load('com_addresses', JPATH_ADMINISTRATOR, 'en-GB', true);

		return parent::display($cachable, $safeurlparams);
	}
}
