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

use Zend\ServiceManager\ServiceManagerAwareInterface;

use Themes\ThemeManager\Theme\ThemeInterface;

interface AdapterInterface extends ServiceManagerAwareInterface
{
	
	/**
	 * Finds all available themes.
	 * 
	 * @return array;
	 */
	public function findAll();
	
	/**
	 * Gets the active theme.
	 * 
	 * @return ThemeInterface
	 */
	public function getActive();
	
	/**
	 * Change the current theme
	 * 
	 * @param ThemeInterface $theme
	 */
	public function changeTheme(ThemeInterface $theme);
	
}