<?php
/**
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Addresses\Site\Helper;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Addresses access helper
 */
class AccessHelper
{
    private bool $ownRecordsLoaded = false;
    private array $ownRecordsById = [];

    /**
     * @param string $table
     * @return void
     */
    public function preloadOwnRecords(string $table): void
    {
        $user  = Factory::getUser();
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('id, created_by')
            ->from($table)
            ->where($db->qn('created_by') . ' = ' . $db->q((int) $user->id));
        $db->setQuery($query);
        $records = $db->loadAssocList();
        foreach ($records as $record) {
            $this->ownRecordsById[$record['id']] = (int) $record['created_by'];
        }
        $this->ownRecordsLoaded = true;
    }

    /**
     * @param int|null $id
     * @return bool
     * @throws \Exception
     */
    public function canAccessOwnRecord(?int $id = null): bool
    {
        if (!$this->ownRecordsLoaded) {
            throw new \Exception('Please preload records before calling the ' . __METHOD__ . ' method');
        }
        $app = Factory::getApplication();
        if (empty($id)) {
            $id     = $app->input->getInt('id');
            $params = $app->getParams();

            $paramId = $params->get('id');
            if ($paramId && !$id) {
                $id = $paramId;
            }
        }
        $user   = Factory::getUser();
        $userId = $this->ownRecordsById[$id] ?? 0;
        return $userId === (int) $user->id && $user->authorise('core.edit.own', 'com_addresses');
    }
}
