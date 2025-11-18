<?php
/**
 * @package     Com_addresses
 * @version     1.3.3
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Component\Addresses\Site\Helper\DatetimeHelper;
?>
<div class="page-header">
    <h1>
        <?= $this->item->title; ?>
    </h1>
</div>
<div class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th class="item-address">
				<?= Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_ADDRESS'); ?>
			</th>
			<td>
				<?= $this->item->address; ?>
			</td>
		</tr>
		<tr>
			<th class="item-postcode">
				<?= Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_POSTCODE'); ?>
			</th>
			<td>
				<?= $this->item->postcode; ?>
			</td>
		</tr>
		<tr>
			<th class="item-city">
				<?= Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_CITY'); ?>
			</th>
			<td>
				<?= $this->item->city; ?>
			</td>
		</tr>
		<tr>
			<th class="item-country">
				<?= Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_COUNTRY'); ?>
			</th>
			<td>
				<?= $this->item->country; ?>
			</td>
		</tr>
		<tr>
			<th class="item-created_by">
				<?= Text::_('COM_ADDRESSES_HEADING_FRONTEND_DETAIL_ADDRESS_CREATED_BY'); ?>
			</th>
			<td>
				<?= $this->item->created_by; ?>
			</td>
		</tr>
	</table>
</div>
<p><a href="index.php?option=com_addresses&view=addresses" class="btn btn-primary">Back to list</a></p>