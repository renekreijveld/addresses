<?php
/**
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Site\Model;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\Component\Addresses\Administrator\Helper\FormHelper;
use Joomla\Component\Addresses\Site\Helper\DatabaseHelper;
use Joomla\CMS\Form\Form;

/**
 * Addresses list model
 */
class AddressesModel extends ListModel
{
    /**
     * @param    array          $config     An optional associative array of configuration settings
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
     * @param string $ordering An optional ordering field
     * @param string $direction An optional direction (asc|desc)
     *
     * @return void
     *
     * @throws Exception
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {
        $app   = Factory::getApplication();
        $input = $app->input;

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        // Load the list state
        $this->setState('list.start', $input->get('limitstart', 0, 'uint'));
        $this->setState('list.limit', $input->get('limit', $app->get('list_limit', 20), 'uint'));

        // List state information
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data
     *
     * @return    DatabaseQuery
     * @since    1.6
     */
    protected function getListQuery()
    {
        $query = $this->_db->getQuery(true);

        $query->select('a.id, a.ordering, a.title');
        $query->select('a.address, a.postcode, a.city');
        $query->select('a.country, a.state');

        $query->from('`#__addresses` AS a');

        $query->select('i.name AS `created_by`');
        $query->leftJoin($this->_db->qn('#__users') . ' AS `i` ON i.id = a.created_by');

        $query->where('a.state = 1');

        // Search for this word
        $searchWord = $this->getState('filter.search');

        // Search in these columns
        $searchColumns = [
            'a.title',
            'a.address',
            'a.postcode',
            'a.city',
            'a.country',
            'i.name',
        ];

        if (!empty($searchWord)) {
            if (stripos($searchWord, 'id:') === 0) {
                // Build the ID search
                $idPart = (int) substr($searchWord, 3);
                $query->where($this->_db->qn('a.id') . ' = ' . $this->_db->q($idPart));
            } else {
                $query = DatabaseHelper::buildSearchQuery($searchWord, $searchColumns, $query);
            }
        }

        $query->group($this->_db->qn('a.id'));

        // Add the list ordering clause
        $orderCol  = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        if ($orderCol && $orderDirn) {
            $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));
        } else {
            $query->order('a.ordering');
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
