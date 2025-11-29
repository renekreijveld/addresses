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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Addresses\Site\Helper\DatetimeHelper;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<?php if ($this->params->get('show_page_heading')): ?>
    <div class="page-header">
        <h1>
            <?php if ($this->escape($this->params->get('page_heading'))): ?>
                <?= $this->escape($this->params->get('page_heading')); ?>
            <?php else : ?>
                <?= $this->escape($this->params->get('page_title')); ?>
            <?php endif; ?>
        </h1>
    </div>
<?php endif; ?>
<form action="<?= Route::_('index.php?option=com_addresses&view=addresses'); ?>" method="get" name="adminForm"
    id="adminForm">
    <div id="filter-bar" class="btn-toolbar mb-2">
        <div class="input-group mb-2">
            <input type="text" name="filter_search" id="filter-search" class="form-control"
                placeholder="<?= Text::_('JSEARCH_FILTER'); ?>..."
                value="<?= $this->escape($this->state->get('filter.search')); ?>"
                title="<?= Text::_('JSEARCH_FILTER'); ?>" />
            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit"><?= Text::_('JSEARCH_FILTER'); ?></button>
                <button class="btn btn-secondary" id="clear-search"
                    type="button"><?= Text::_('JSEARCH_FILTER_CLEAR'); ?></button>
            </div>
        </div>
        <div class="input-group mb-2 ms-2">
            <select name="filter_catid" id="filter-catid" class="form-select" onchange="this.form.submit()">
                <option value=""><?= Text::_('COM_ADDRESSES_FILTER_CATEGORY'); ?></option>
                <?php foreach ($this->categories as $category): ?>
                    <option value="<?= $category->id; ?>" <?= ($this->state->get('filter.catid') == $category->id) ? 'selected' : ''; ?>>
                        <?= $this->escape($category->title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="item-title">
                        <?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_FRONTEND_LIST_ADDRESSES_TITLE', 'a.title', $listDirn, $listOrder); ?>
                    </th>
                    <th class="item-address">
                        <?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_FRONTEND_LIST_ADDRESSES_ADDRESS', 'a.address', $listDirn, $listOrder); ?>
                    </th>
                    <th class="item-postcode">
                        <?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_FRONTEND_LIST_ADDRESSES_POSTCODE', 'a.postcode', $listDirn, $listOrder); ?>
                    </th>
                    <th class="item-city">
                        <?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_FRONTEND_LIST_ADDRESSES_CITY', 'a.city', $listDirn, $listOrder); ?>
                    </th>
                    <th class="item-country">
                        <?= HTMLHelper::_('grid.sort', 'COM_ADDRESSES_HEADING_FRONTEND_LIST_ADDRESSES_COUNTRY', 'a.country', $listDirn, $listOrder); ?>
                    </th>
                    <th class="item-category">
                        <?= Text::_('COM_ADDRESSES_HEADING_FRONTEND_LIST_ADDRESSES_CATEGORY'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item): ?>
                    <tr class="<?= ($i % 2) ? 'odd' : 'even'; ?>">
                        <td class="item-title">
                            <a
                                href="<?= Route::_('index.php?option=com_addresses&view=address&id=' . $item->id . '&Itemid=' . $this->item_id); ?>">
                                <?= $item->title; ?>
                            </a>
                        </td>
                        <td class="item-address">
                            <?= $item->address; ?>
                        </td>
                        <td class="item-postcode">
                            <?= $item->postcode; ?>
                        </td>
                        <td class="item-city">
                            <?= $item->city; ?>
                        </td>
                        <td class="item-country">
                            <?= $item->country; ?>
                        </td>
                        <td class="item-category">
                            <?= $item->category ?? ''; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination center">
            <?= $this->pagination->getListFooter(); ?>
        </div>
        <input type="hidden" name="view" value="addresses" />
        <input type="hidden" name="option" value="com_addresses" />
        <input type="hidden" name="Itemid" value="<?= $this->item_id; ?>" />
        <input type="hidden" name="filter_order" value="<?= $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?= $listDirn; ?>" />
    </div>
</form>