<?php
/**
 * @package     com_addresses
 * @version     1.0.0
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Component\Addresses\Site\Helper\DatetimeHelper;
?>
<?php if ($this->params->get('show_page_heading')): ?>
	<div class="page-header">
		<h1>
			<?php if ($this->escape($this->params->get('page_heading'))): ?>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			<?php else: ?>
				<?php echo $this->escape($this->params->get('page_title')); ?>
			<?php endif; ?>
		</h1>
	</div>
<?php endif; ?>
<div class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th class="item-title">
				<?php echo Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_TITLE'); ?>
			</th>
			<td>
				<?php echo $this->item->title; ?>
			</td>
		</tr>
		<tr>
			<th class="item-address">
				<?php echo Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_ADDRESS'); ?>
			</th>
			<td>
				<?php echo $this->item->address; ?>
			</td>
		</tr>
		<tr>
			<th class="item-postcode">
				<?php echo Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_POSTCODE'); ?>
			</th>
			<td>
				<?php echo $this->item->postcode; ?>
			</td>
		</tr>
		<tr>
			<th class="item-city">
				<?php echo Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_CITY'); ?>
			</th>
			<td>
				<?php echo $this->item->city; ?>
			</td>
		</tr>
		<tr>
			<th class="item-country">
				<?php echo Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_COUNTRY'); ?>
			</th>
			<td>
				<?php echo $this->item->country; ?>
			</td>
		</tr>
		<tr>
			<th class="item-created_by">
				<?php echo Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_CREATED_BY'); ?>
			</th>
			<td>
				<?php echo $this->item->created_by; ?>
			</td>
		</tr>
	</table>
</div>