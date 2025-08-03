<?php
/**
 * @package    Bruno config files Installer
 * @author     René Kreijveld
 * @copyright  Copyright © 2025 René Kreijveld Webdevelopment
 * @license    GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Log\Log;

/**
 * Script file for Website Root Files installer
 */
class plgInstallerScript extends InstallerScript
{
    /**
     * Called before any type of action
     *
     * @param   string  $type    Which action is happening (install|uninstall|discover_install|update)
     * @param   object  $parent  The object responsible for running this script
     *
     * @return  boolean  True on success
     */
    public function preflight($type, $parent)
    {
        // Check if we have write permissions to the root directory
        $rootPath = JPATH_ROOT;
        
        if (!is_writable($rootPath)) {
            Factory::getApplication()->enqueueMessage(
                'The website root directory is not writable. Please check permissions.',
                'error'
            );
            return false;
        }

        return true;
    }

    /**
     * Called after install
     *
     * @param   object  $parent  The object responsible for running this script
     *
     * @return  void
     */
    public function install($parent)
    {
        $this->extractFilesToRoot($parent);
    }

    /**
     * Called after update
     *
     * @param   object  $parent  The object responsible for running this script
     *
     * @return  void
     */
    public function update($parent)
    {
        $this->extractFilesToRoot($parent);
    }

    /**
     * Extract files from the installer package to the website root
     *
     * @param   object  $parent  The object responsible for running this script
     *
     * @return  void
     */
    private function extractFilesToRoot($parent)
    {
        $app = Factory::getApplication();
        $rootPath = JPATH_ROOT;
        
        // Get the path where files are temporarily extracted during installation
        $installer = $parent->getParent();
        $sourcePath = $installer->getPath('source');
        
        // Path to the files folder in the installer package
        $filesPath = $sourcePath . '/files';
        
        if (!is_dir($filesPath)) {
            $app->enqueueMessage('No files directory found in installer package.', 'warning');
            return;
        }

        try {
            // Copy files from the installer to the root
            $this->copyDirectory($filesPath, $rootPath);
            
            $app->enqueueMessage(
                'Bruno config files have been successfully extracted.',
                'success'
            );
            
            Log::add(
                'Bruno config files: Files extracted to root directory',
                Log::INFO,
                'installer'
            );
            
        } catch (Exception $e) {
            $app->enqueueMessage(
                'Error extracting files: ' . $e->getMessage(),
                'error'
            );
            
            Log::add(
                'Bruno config files: Error extracting files - ' . $e->getMessage(),
                Log::ERROR,
                'installer'
            );
        }
    }

    /**
     * Recursively copy directory contents
     *
     * @param   string  $source       Source directory
     * @param   string  $destination  Destination directory
     *
     * @return  void
     * @throws  Exception
     */
    private function copyDirectory($source, $destination)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $destinationPath = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!is_dir($destinationPath)) {
                    if (!mkdir($destinationPath, 0755, true)) {
                        throw new Exception("Failed to create directory: $destinationPath");
                    }
                }
            } else {
                $destinationDir = dirname($destinationPath);
                if (!is_dir($destinationDir)) {
                    if (!mkdir($destinationDir, 0755, true)) {
                        throw new Exception("Failed to create directory: $destinationDir");
                    }
                }
                
                if (!copy($item->getPathname(), $destinationPath)) {
                    throw new Exception("Failed to copy file: {$item->getPathname()} to $destinationPath");
                }
                
                // Set proper file permissions
                chmod($destinationPath, 0644);
            }
        }
    }

    /**
     * Called after uninstall
     *
     * @param   object  $parent  The object responsible for running this script
     *
     * @return  void
     */
    public function uninstall($parent)
    {
        $app = Factory::getApplication();
        
        $app->enqueueMessage(
            'Bruno config files uninstalled. Note: Files copied to the root directory were NOT removed for safety reasons.',
            'info'
        );
        
        Log::add(
            'Bruno config files: Extension uninstalled (root files preserved)',
            Log::INFO,
            'installer'
        );
    }
}
