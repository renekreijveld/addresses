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

use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\Component\Addresses\Administrator\Helper\FormHelper;
use Joomla\Component\Addresses\Site\Helper\DatetimeHelper;

/**
 * Addresses detail model
 */
class AddressModel extends FormModel
{
    /**
     * The item to hold data
     *
     * @return object
     */
    protected $_item;

    /**
     * @return void
     * @throws \Exception
     */
    private function fetchItem()
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true);

        $query->select('a.id, a.ordering, a.title');
        $query->select('a.address, a.postcode, a.city');
        $query->select('a.country, a.state');

        $query->from('#__addresses as a');

        $query->select('i.name AS `created_by`');
        $query->leftJoin($this->_db->quoteName('#__users') . ' AS `i` ON i.id = a.created_by');

        $query->where($db->quoteName('a.id') . ' = ' . $db->q($this->getId()));
        $db->setQuery($query);

        try {
            $db->execute();
        } catch (\RuntimeException $e) {
            throw new \Exception($e->getMessage(), 500);
        }

        $this->_item = $db->loadObject();
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function getId(): int
    {
        $app = Factory::getApplication();

        $id     = $app->input->getInt('id');
        $params = $app->getParams();

        $paramId = $params->get('id');
        if ($paramId && $id === null) {
            return (int) $paramId;
        }

        return (int) $id;
    }

    /**
     * Get the data
     *
     * @param null $pk
     *
     * @return  object
     *
     * @throws \Exception
     * @since   1.6
     */
    public function getItem($pk = null)
    {
        if (isset($this->_item)) {
            return $this->_item;
        }

        $this->fetchItem();

        Form::addFormPath(JPATH_ADMINISTRATOR . '/components/com_addresses/forms');
        $form       = $this->loadForm('com_addresses.address', 'address', [
            'control' => 'jform',
            'load_data' => true
        ]);
        $formHelper = new FormHelper($form);
        return $formHelper->appendFieldOptions([$this->_item])->getOne();
    }

    /**
     * Method to get the form.
     *
     * The base form is loaded from XML
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	Form	A JForm object on success, false on failure
     * @throws \Exception
     * @since	1.6
     */
    public function getForm($data = [], $loadData = true)
    {
        Form::addFormPath(JPATH_ADMINISTRATOR . '/components/com_addresses/forms');

        $app     = Factory::getApplication();
        $id      = $app->input->getInt('id');
        $params  = $app->getParams();
        $paramId = $params->get('id');
        if ($paramId && !$id) {
            $id = $paramId;
        }
        if (empty($id)) {
            $loadData = false;
        }

        // Get the form
        $form = $this->loadForm('com_addresses.address', 'address', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }
}
