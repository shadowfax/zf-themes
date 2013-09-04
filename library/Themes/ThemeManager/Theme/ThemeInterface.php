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
namespace Themes\ThemeManager\Theme;

interface ThemeInterface
{
	/**
	 * Gets the name of the theme.
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Sets the name of the theme.
	 * 
	 * @param string $name
	 */
	public function setName($name);
	
	/**
	 * Gets a description of the theme.
	 * 
	 * @return string
	 */
	public function getDescription();
	
	/**
	 * Sets a description of the theme.
	 * 
	 * @param string $description
	 */
	public function setDescription($description);
	
	/**
	 * Gets if the theme is the active theme.
	 * 
	 * @return boolean
	 */
	public function getActive();
	
	/**
	 * Sets if the theme is the active theme.
	 * 
	 * @param boolean $active
	 */
	public function setActive($active);
	
	/**
	 * Gets if the theme is the active theme
	 * 
	 * @return boolean
	 */
	public function isActive();
	
	/**
	 * Populate the class.
	 * 
	 * @param array $data;
	 */
	public function populate(array $data);
	
	/**
	 * Gets the folder name for this theme.
	 * 
	 * @return string
	 */
	public function getFolder();
	
	/**
	 * Get the layout name
	 * 
	 * @return string
	 */
	public function getLayoutName();
}