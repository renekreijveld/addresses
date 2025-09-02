<?php
/**
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

/** @var \Joomla\Component\Addresses\Administrator\View\Address\HtmlView $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_contenthistory');
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('com_contenthistory.admin-history-versions');
?>
<form action="<?= Route::_('index.php?option=com_addresses&layout=edit&id=' . $this->item->id); ?>" method="post"
	enctype="multipart/form-data" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<fieldset class="adminform">
		<div class="row">
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('id'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('id'); ?>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('state'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('state'); ?>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('created_by'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('created_by'); ?>
					</div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('title'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('title'); ?>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('catid'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('catid'); ?>
					</div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('address'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('address'); ?>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('postcode'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('postcode'); ?>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('city'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('city'); ?>
					</div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-4">
				<div class="control-group">
					<div class="control-label">
						<?= $this->form->getLabel('country'); ?>
					</div>
					<div class="controls">
						<?= $this->form->getInput('country'); ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>			
	<input type="hidden" name="task" value="" />
	<?= HTMLHelper::_('form.token'); ?>
	<div id="validation-form-failed" data-backend-detail="address"
		data-message="<?= $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>">
	</div>
</form>