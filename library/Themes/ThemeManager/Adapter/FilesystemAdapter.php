<?php
/**
 * ZF-Themes
 * 
 * Theme engine for Zend Framework 2
 * 
 * @author    Juan Pedro Gonzalez
 * @copyright Copyright (c) 2013 Juan Pedro Gonzalez
 * @link      http://github.com/shadowfax
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */
namespace Themes\ThemeManager\Adapter;

use Themes\ThemeManager\Theme\ThemeInterface;

use Zend\Json\Json;

use Themes\ThemeManager\Theme\Theme;

use Themes\ModuleManager\Feature\ThemeProviderInterface;

use Zend\ServiceManager\ServiceManager;

class FilesystemAdapter implements AdapterInterface
{
	/**
	 * Service manager instance
	 * 
	 * @var Zend\ServiceManager\ServiceManager
	 */
	protected $serviceManager;
	
	/**
	 * Finds all available themes.
	 * 
	 * @return array;
	 */
	public function findAll()
	{
		$themes = array();
		$moduleManager = $this->serviceManager->get('ModuleManager');
		$themeManager  = $this->serviceManager->get('ThemeManager');
		$module_names  = $moduleManager->getModules();
		foreach ($module_names as $module_name) {
			$module = $moduleManager->getModule($module_name);
			if ($module instanceof ThemeProviderInterface) {
				// Get the themes
				$themes_path = $module->getDir() . DIRECTORY_SEPARATOR . $themeManager->getThemeFolder();
				if (is_dir($themes_path)) {
					$dir = new DirectoryIterator($themes_path);
					foreach ($dir as $fileinfo) {
						if ($fileinfo->isDir() && !$fileinfo->isDot()) {
							// Probably got a theme
							// Check the info file
							$info_file = $themes_path . DIRECTORY_SEPARATOR . $fileinfo->getFilename() . DIRECTORY_SEPARATOR . 'info.json';
							if (is_readable($info_file)) {
								$theme_info = file_get_contents($info_file);
								$theme_info = Json::decode($theme_info, Json::TYPE_ARRAY);
								$theme_info = array_change_key_case($theme_info, CASE_LOWER);
								$theme = new Theme();
								$theme->populate($theme_info);
								// Add to found themes
								$themes[] = $theme;
							}
						}
					}
				}
			}
		}
		return $themes; 
	}
	
	/**
	 * Gets the active theme.
	 * 
	 * @return ThemeInterface
	 */
	public function getActive()
	{
		$moduleManager = $this->serviceManager->get('ModuleManager');
		$themeManager  = $this->serviceManager->get('ThemeManager');
		$module_names  = $moduleManager->getModules();
		foreach ($module_names as $module_name) {
			$module = $moduleManager->getModule($module_name);
			if ($module instanceof ThemeProviderInterface) {
				// Get the themes
				$themes_path = $module->getDir() . DIRECTORY_SEPARATOR . $themeManager->getThemeFolder();
				if (is_dir($themes_path)) {
					$dir = new DirectoryIterator($themes_path);
					foreach ($dir as $fileinfo) {
						if ($fileinfo->isDir() && !$fileinfo->isDot()) {
							// Probably got a theme
							// Check the info file
							$info_file = $themes_path . DIRECTORY_SEPARATOR . $fileinfo->getFilename() . DIRECTORY_SEPARATOR . 'info.json';
							if (is_readable($info_file)) {
								$theme_info = file_get_contents($info_file);
								$theme_info = Json::decode($theme_info, Json::TYPE_ARRAY);
								$theme_info = array_change_key_case($theme_info, CASE_LOWER);
								$theme = new Theme();
								$theme->populate($theme_info);
								
								if ($theme->isActive()) {
									return $theme;
								}
							}
						}
					}
				}
			}
		}
		// No active theme, return default!
		return $themeManager->getDefaultTheme();
	}
	
	/**
	 * Change the current theme
	 * 
	 * @param ThemeInterface $theme
	 */
	public function changeTheme(ThemeInterface $theme)
	{
		// Unset all the themes and activate the new one
		$moduleManager = $this->serviceManager->get('ModuleManager');
		$themeManager  = $this->serviceManager->get('ThemeManager');
		$module_names  = $moduleManager->getModules();
		foreach ($module_names as $module_name) {
			$module = $moduleManager->getModule($module_name);
			if ($module instanceof ThemeProviderInterface) {
				// Get the themes
				$themes_path = $module->getDir() . DIRECTORY_SEPARATOR . $themeManager->getThemeFolder();
				if (is_dir($themes_path)) {
					$dir = new DirectoryIterator($themes_path);
					$theme_name = $theme->getName();
					foreach ($dir as $fileinfo) {
						if ($fileinfo->isDir() && !$fileinfo->isDot()) {
							// Probably got a theme
							// Check the info file
							$info_file = $themes_path . DIRECTORY_SEPARATOR . $fileinfo->getFilename() . DIRECTORY_SEPARATOR . 'info.json';
							if (is_readable($info_file)) {
								$theme_info = file_get_contents($info_file);
								$theme_info = Json::decode($theme_info, Json::TYPE_ARRAY);
								$theme_info = array_change_key_case($theme_info, CASE_LOWER);
								if (strcmp($theme_info, $theme_name) !== 0) {
									if (isset($theme_info['active'])) {
										if ($theme_info['active']) {
											$theme_info['active'] = false;
											$theme_info = Json::encode($theme_info);
											file_put_contents($info_file, $theme_info);
										}
									}
								} else {
									$theme_info['active'] = true;
									$theme_info = Json::encode($theme_info);
									file_put_contents($info_file, $theme_info);
								}
							}
						}
					}
				}
			}
		}
		
		return $theme;
	}
	
	
	/**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
    	$this->serviceManager = $serviceManager;
    	return $this;
    }
	
}