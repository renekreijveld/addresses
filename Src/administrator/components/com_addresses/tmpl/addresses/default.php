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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Component\Addresses\Administrator\Helper\AddressesHelper;
?>
<?php $listOrder = $this->listOrder; ?>
<?php $listDirn  = $this->listDirn;
$saveOrder = $listOrder;
if ($listOrder && !empty($this->items)) {
	$this->saveOrderingUrl = 'index.php?option=com_addresses&task=addresses.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?= Route::_('index.php?option=com_addresses&view=addresses'); ?>" method="post" name="adminForm" id="adminForm" data-list-order="<?= $listOrder; ?>">
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?= $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
		<?php else : ?>
			<?= LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<div id="j-main-container">
			<?php endif; ?>
			<table class="table table-striped" id="addressList">
				<thead>
					<tr>
						<?php if (isset($this->items[0]->ordering)): ?>
							<th width="1%" class="nowrap center hidden-phone">
								<?= HTMLHelper::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
							</th>
						<?php endif; ?>
						<th width="1%" class="nowrap center">
							<?= HTMLHelper::_('grid.checkall'); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'JCATEGORY', 'c.title', $listDirn, $listOrder); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_ADDRESS', 'a.address', $listDirn, $listOrder); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_POSTCODE', 'a.postcode', $listDirn, $listOrder); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_CITY', 'a.city', $listDirn, $listOrder); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_COUNTRY', 'a.country', $listDirn, $listOrder); ?>
						</th>
						<th class="left">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_STATE', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap">
							<?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_BACKEND_LIST_ADDRESSES_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody <?php if ($saveOrder): ?> class="js-draggable" data-url="<?= $this->saveOrderingUrl; ?>"
						data-direction="<?= strtolower($listDirn); ?>" data-nested="true" <?php endif; ?>>
					<?php
					foreach ($this->items as $i => $item):
						$ordering   = ($listOrder == 'a.ordering');
						$canCreate  = $this->user->authorise('core.create', 'com_addresses');
						$canEdit    = $this->user->authorise('core.edit', 'com_addresses');
						$canCheckin = $this->user->authorise('core.manage', 'com_addresses');
						$canChange  = $this->user->authorise('core.edit.state', 'com_addresses');
						?>
						<tr class="row<?= $i % 2; ?>" data-draggable-group="1">
							<td class="order nowrap center hidden-phone">
								<?php
								$iconClass = '';
								if (!$canChange) {
									$iconClass = ' inactive';
								} elseif (!$this->saveOrder) {
									$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
								}
								?>
								<span class="sortable-handler<?= $iconClass; ?>">
									<span class="icon-ellipsis-v"></span>
								</span>
								<?php if ($canChange && $this->saveOrder): ?>
									<input type="text" style="display:none" name="order[]" size="5"
										value="<?= $item->ordering; ?>" class="width-20 text-area-order " />
								<?php endif; ?>
							</td>
							<td class="center">
								<?= HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>
							<td>
								<a href="<?= Route::_('index.php?option=com_addresses&task=address.edit&id=' . $item->id); ?>">
									<?= $item->title; ?>
								</a>
							</td>
							<td>
								<?= $item->category; ?>
							</td>
							<td>
								<?= $item->address; ?>
							</td>
							<td>
								<?= $item->postcode; ?>
							</td>
							<td>
								<?= $item->city; ?>
							</td>
							<td>
								<?= $item->country; ?>
							</td>
							<td>
								<?= HTMLHelper::_('jgrid.published', $item->state, $i, 'addresses.', $canChange, 'cb'); ?>
							</td>
							<td>
								<a href="<?= Route::_('index.php?option=com_addresses&task=address.edit&id=' . $item->id); ?>">
									<?= $item->id; ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="pagination center">
				<?= $this->pagination->getListFooter(); ?>
			</div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?= $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?= $listDirn; ?>" />
			<?= HTMLHelper::_('form.token'); ?>
		</div>
	</div>
</form>