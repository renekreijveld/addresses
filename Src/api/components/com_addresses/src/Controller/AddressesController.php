<?php

/**
 * @package     com_addresses
 * @version     1.0.0
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Api\Controller;

use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\MVC\Controller\Exception\Save;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The requests controller
 *
 * @since  4.0.0
 */
class AddressesController extends ApiController
{
    /**
     * The content type of the item.
     *
     * @var    string
     * @since  4.0.0
     */
    protected $contentType = 'addresses';

    /**
     * The default view for the display method.
     *
     * @var    string
     * @since  3.0
     */
    protected $default_view = 'addresses';

    public function displayList(): AddressesController
    {
        $this->input->set('page', ['offset' => 0, 'limit' => 99999999]);

        parent::displayList();

        return $this;
    }

    public function search(): AddressesController
    {
        $this->input->set('page', ['offset' => 0, 'limit' => 99999999]);

        $search    = $this->input->get('search', '', 'string');
        $modelName = $this->input->get('model', $this->contentType);

        /** @var ListModel $model */
        $model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

        if ($search) {
            $model->setState('filter.search', $search);
        }

        parent::displayList();

        return $this;
    }

    public function postcode(): AddressesController
    {
        $this->input->set('page', ['offset' => 0, 'limit' => 99999999]);

        $postcode = $this->input->get('postcode', '', 'string');
        $postcode = substr($postcode, 0, 4) . ' ' . substr($postcode, 4, 2);

        $modelName = $this->input->get('model', $this->contentType);

        /** @var ListModel $model */
        $model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

        if ($postcode) {
            $model->setState('filter.postcode', $postcode);
        }

        parent::displayList();

        return $this;
    }

    public function add(): AddressesController
    {
        $data = json_decode($this->input->json->getRaw(), false);

        parent::add();

        return $this;
    }

    public function delete($id = null): AddressesController
    {
        $data = json_decode($this->input->json->getRaw(), false);

        parent::delete($data->id);

        return $this;
    }
}
