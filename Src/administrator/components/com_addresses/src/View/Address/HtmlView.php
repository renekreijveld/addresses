<?php
/**
 * @package     Com_addresses
 * @version     1.3.3
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Administrator\View\Address;

// No direct access
defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\Component\Addresses\Administrator\Helper\AddressesHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Layout\FileLayout;

/**
 * Addresses detail view
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The Form object
	 *
	 * @var    Form
	 * @since  1.5
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var    object
	 * @since  1.5
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var    object
	 * @since  1.5
	 */
	protected $state;

	/**
	 * Display the view
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$model       = $this->getModel();
		$this->form  = $model->getForm();
		$this->item  = $model->getItem();
		$this->state = $model->getState();

		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}

		$document = Factory::getDocument();
		$wa       = $document->getWebAssetManager();
		$wa->registerAndUseStyle('my-style', 'components/com_addresses/assets/css/addresses.css');
		$wa->registerAndUseScript('my-script', 'components/com_addresses/assets/js/detail.js');

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$user  = Factory::getApplication()->getIdentity();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out)) {
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		} else {
			$checkedOut = false;
		}

		$canDo = AddressesHelper::getActions();

		$title = Text::_('COM_ADDRESSES_TITLE_ADDRESS');
		$icon  = 'fa fa-file-alt';

		$layout = new FileLayout('joomla.toolbar.title');
		$html   = $layout->render([
			'title' => $title,
			'icon' => $icon
		]);

		$app                  = Factory::getApplication();
		$app->JComponentTitle = str_replace('icon-', '', $html);
		$title                = strip_tags($title) . ' - ' . $app->get('sitename') . ' - ' . Text::_('JADMINISTRATION');
		Factory::getDocument()->setTitle($title);

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo['core.edit'] || ($canDo['core.create']))) {
			ToolbarHelper::apply('address.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
		}

		if (!$checkedOut && ($canDo['core.create'])) {
			ToolbarHelper::custom('address.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo['core.create']) {
			ToolbarHelper::custom('address.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id)) {
			ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CANCEL');
		} else {
			ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
