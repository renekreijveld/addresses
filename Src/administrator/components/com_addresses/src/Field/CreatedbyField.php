<?php
/**
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Factory;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\Database\ParameterType;


/**
 * The form field implementation
 */
class CreatedbyField extends ListField
{
	/**
	 * The form field type
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'createdby';

	/**
	 * Method to get the field input markup
	 *
	 * @return	string	The field input markup
	 * @since	1.6
	 */
	protected function getInput()
	{
		$user = Factory::getApplication()->getIdentity();

		$userExists = true;

		if ($this->value) {
			$db    = Factory::getContainer()->get('DatabaseDriver');
			$query = $db->getQuery(true)
				->select('id')
				->from('#__users')
				->where($db->quoteName('id') . ' = :id')
				->bind(':id', $this->value, ParameterType::INTEGER);
			$db->setQuery($query);
			$userId = $db->loadResult();
			if ($userId) {
				$user = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById((int) $this->value);
			} else {
				$userExists  = false;
				$this->value = $user->id;
			}
		} else {
			$this->value = $user->id;
		}

		$html = '';
		if ($userExists) {
			$html = $user->name . " (" . $user->username . ")";
		}

		$html .= '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '" />';

		return $html;
	}
}
