<?php

/**
 * @package     com_addresses
 * @version     1.0.0
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

define('MODIFIED', 1);
define('NOT_MODIFIED', 2);

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\Installer;

return new class () implements InstallerScriptInterface {

    private string $minimumJoomla = '5.2.0';
    private string $minimumPhp    = '8.3.0';
    private $logfile = JPATH_ADMINISTRATOR . '/logs/package_script.log';

    public function install(InstallerAdapter $adapter): bool
    {
        $this->log("running install");

        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {
        $this->log("running update");

        return true;
    }

    public function uninstall(InstallerAdapter $adapter): bool
    {
        $this->log("running uninstall");

        return true;
    }

    public function preflight(string $type, InstallerAdapter $adapter): bool
    {
        $this->log("running preflight");

        if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_PHP'), $this->minimumPhp), 'error');
            return false;
        }

        if (version_compare(JVERSION, $this->minimumJoomla, '<')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_JOOMLA'), $this->minimumJoomla), 'error');
            return false;
        }

        return true;
    }

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        $this->log("running postflight");

        return true;
    }

    /**
     * Logs a message to the package log file.
     *
     * This method writes a log entry with the current date and time,
     * prefixed with "Addresses Package: ", to the package log file.
     *
     * @param string $message The message to log.
     *
     * @return void
     */
    private function log($message): void
    {
        error_log(date("Y-m-d H:i:s ") . "Addresses Package: $message\n", 3, $this->logfile);
    }

};

