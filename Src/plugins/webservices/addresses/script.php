<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\Database\DatabaseDriver;

return new class () implements InstallerScriptInterface {
    private string $minimumJoomla = '5.2';
    private string $minimumPhp = '8.2';

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        // Enable plugin on first installation only.
        if ($type === 'install' || $type === 'discover_install') {
            return $this->publish();
        }

        return true;
    }

    private function publish(): bool
    {
        /** @var DatabaseDriver $db */
        $db    = Factory::getContainer()->get('DatabaseDriver');
        $query = sprintf(
            'UPDATE %s SET %s = 1 WHERE %s = %s AND %s = %s',
            $db->quoteName('#__extensions'),
            $db->quoteName('enabled'),
            $db->quoteName('type'),
            $db->quote('plugin'),
            $db->quoteName('name'),
            $db->quote('PLG_WEBSERVICES_ADDRESSES')
        );
        $db->setQuery($query);

        return $db->execute();
    }

    public function install(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function uninstall(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function preflight(string $type, InstallerAdapter $adapter): bool
    {
        return true;
    }
};
