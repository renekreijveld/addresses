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

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Component\Addresses\Administrator\Helper\FormHelper;
use Joomla\Component\Addresses\Administrator\Helper\AddressesHelper;
use Joomla\CMS\Form\Form;

/**
 * Addresses model
 */
class AddressesModel extends ListModel
{
	/**
	 * @var		array		An array with the filtering columns
	 */
	protected $filter_fields;

	/**
	 * Constructor
	 *
	 * @param    array    		An optional associative array of configuration settings
	 *
	 * @see      JController
	 * @since    1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id',
				'a.id',
				'ordering',
				'a.ordering',
				'title',
				'a.title',
				'address',
				'a.address',
				'postcode',
				'a.postcode',
				'city',
				'a.city',
				'country',
				'a.country',
				'state',
				'a.state',
				'created_by',
				'a.created_by',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state
	 *
	 * Note. Calling getState in this method will result in recursion
	 *
	 * @param null $ordering
	 * @param null $direction
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables
		$app = Factory::getApplication('administrator');

		// Load the filter state
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'int');
		$this->setState('filter.state', $published);

		// List state information
		$value = $app->input->get('limit', $app->get('list_limit', 20), 'uint');
		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);

		// Load the parameters
		$params = ComponentHelper::getParams('com_addresses');
		$this->setState('params', $params);

		// List state information
		parent::populateState('a.ordering', 'asc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	DatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		$query = $this->_db->getQuery(true);

		$query->select('a.id, a.ordering, a.title');
		$query->select('a.address, a.postcode, a.city');
		$query->select('a.country, a.state');

		$query->from('`#__addresses` AS a');

		$query->select('i.name AS `created_by`');
		$query->select('c.title AS `category`');
		$query->leftJoin($this->_db->quoteName('#__users') . ' AS `i` ON i.id = a.created_by');
		$query->leftJoin($this->_db->quoteName('#__categories') . ' AS `c` ON (c.id = a.catid AND c.extension = ' . $this->_db->q('com_addresses') . ')');

		// Filter by published state
		$state = $this->getState('filter.published');

		if (is_numeric($state)) {
			$query->where('a.state = ' . (int) $state);
		} elseif ($state !== '*') {
			$query->where('(a.state IN (0, 1))');
		}

		// Search for this word
		$searchPhrase = $this->getState('filter.search');

		// Search in these columns
		$searchColumns = array(
			'a.title',
			'i.name',
			'a.address',
			'a.city',
			'a.country',
		);

		if (!empty($searchPhrase)) {
			if (stripos($searchPhrase, 'id:') === 0) {
				// Build the ID search
				$idPart = (int) substr($searchPhrase, 3);
				$query->where($this->_db->quoteName('a.id') . ' = ' . $this->_db->q($idPart));
			} else {
				// Build the search query from the search word and search columns
				$query = AddressesHelper::buildSearchQuery($searchPhrase, $searchColumns, $query);
			}
		}

		$searchCategory = $this->getState('filter.catid');
		if (!empty($searchCategory)) {
			$query->where($this->_db->quoteName('a.catid') . ' = ' . $this->_db->q($searchCategory));
		}

		// Search for a specific postcode (only the first 4 digits)
		$searchPostcode = $this->getState('filter.postcode');
		if (!empty($searchPostcode)) {
			$searchPostcode = '%' . $searchPostcode . '%';
			$query->where($this->_db->quoteName('a.postcode') . ' LIKE ' . $this->_db->q($searchPostcode));
		}

		$query->group($this->_db->quoteName('a.id'));

		// Add the list ordering clause
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn) {
			$query->order($this->_db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		Form::addFormPath(JPATH_ADMINISTRATOR . '/components/com_addresses/forms');
		$form       = $this->loadForm('com_addresses.address', 'address', [
			'control' => 'jform',
			'load_data' => true
		]);
		$formHelper = new FormHelper($form);
		return $formHelper->appendFieldOptions(parent::getItems())->getAll();
	}
}
