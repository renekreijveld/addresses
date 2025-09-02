<?php
/**
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Administrator\Model;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\UCM\UCMType;
use Joomla\Component\Addresses\Administrator\Helper\AddressesHelper;

/**
 * Addresses model
 */
class AddressModel extends AdminModel
{
	/**
	 * @var		string	The prefix to use with controller messages
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_ADDRESSES';

	/**
	 * Method to get the record form.
	 *
	 * @param    array $data An optional array of data for the form to interogate
	 * @param bool $loadData
	 *
	 * @return bool A JForm object on success, false on failure
	 * @throws \Exception
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form
		$form = $this->loadForm('com_addresses.address', 'address', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form
	 *
	 * @return	mixed	The data for the form
	 * @throws  \Exception
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data
		$data = Factory::getApplication()->getUserState('com_addresses.edit.address.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {
			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db    = Factory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->qn('#__addresses'));
				$db->setQuery($query);
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}

	/**
	 * Method to initialize member variables used by batch methods and other methods like saveorder()
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 * @since   3.8.2
	 */
	public function initBatch()
	{
		if ($this->batchSet === null) {
			$this->batchSet = true;

			// Get current user
			$this->user = Factory::getUser();

			// Get table
			$this->table = $this->getTable();

			// Get table class name
			$tc                   = explode('\\', \get_class($this->table));
			$this->tableClassName = end($tc);

			if ($this->typeAlias === null) {
				$this->typeAlias = '';
			}

			// Get UCM Type data
			$this->contentType = new UCMType;
			$this->type        = $this->contentType->getTypeByTable($this->tableClassName)
				?: $this->contentType->getTypeByAlias($this->typeAlias);
		}
	}

	/**
	 * @param $data
	 * @return bool
	 * @throws \Exception
	 */
	public function save($data)
	{
		foreach ($this->getForm()->getFieldset() as $field) {
			if ($field->type === 'Calendar') {
				$format = $field->getAttribute('format');
				if ($data[$field->fieldname] === '') {
					$data[$field->fieldname] = null;
				} elseif ($format) {
					$phpFormat = AddressesHelper::convertStrftimeToDateTimeFormat($format);
					$date      = \DateTime::createFromFormat($phpFormat, $data[$field->fieldname]);
					if ($date !== false) {
						$data[$field->fieldname] = $date->format($phpFormat);
					}
				}
			}
			if ($field->type === 'Tag' && isset($data[$field->fieldname]) && is_array($data[$field->fieldname])) {
				$data[$field->fieldname] = implode(',', $data[$field->fieldname]);
			}
		}
		return parent::save($data);
	}
}
