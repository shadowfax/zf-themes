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

use Themes\ThemeManager\Theme\Exception\MissingThemeNameException;

abstract class AbstractTheme implements ThemeInterface
{

	protected $name;
	
	protected $description;
	
	protected $active;
	
	/**
	 * Gets the name of the theme.
	 * 
	 * @return string
	 */
	public function getName() 
	{
		return $this->name;
	}
	
	/**
	 * Sets the name of the theme.
	 * 
	 * @param string $name
	 */
	public function setName($name)
	{
		$name = trim($name);
		if (empty($name)) {
			throw new \Exception('The theme name may not be empty');
		}
		$this->name = trim($name);
		return $this;
	}
	
	
	/**
	 * Gets a description of the theme.
	 * 
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Sets a description of the theme.
	 * 
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	
	/**
	 * Gets if the theme is the active theme.
	 * 
	 * @return boolean
	 */
	public function getActive()
	{
		return $this->active;
	}
	
	/**
	 * Sets if the theme is the active theme.
	 * 
	 * @param boolean $active
	 */
	public function setActive($active)
	{
		// TODO: Event or something to notify the Theme manager about this change
		$this->active = $active;
		return $this;
	}
	
	public function isActive()
	{
		return $this->active;
	}
	
	/**
	 * Populate the class.
	 * 
	 * @param array $data;
	 */
	public function populate(array $data)
	{
		$data = array_change_key_case($data, CASE_LOWER);
		
		if (!isset($data['name'])) {
			throw new MissingThemeNameException('The theme name is not set', 500);
		}
		$name = trim($data['name']);
		if (empty($name)) {
			throw new MissingThemeNameException('The theme name is not set', 500);
		}
		
		$this->name = $name;
		
		if (!array_key_exists('active', $data)) {
			$this->active = false;
		} elseif (!is_bool($data['active'])) {
			$this->active = false;
		} else {
			$this->active = $data['active'];
		}
		
		// Non-mandatory fields
		$this->description = isset($data['description']) ? $data['description'] : '';
		
		// finally
		return $this;
		
	}
	

	/**
	 * Gets the folder name for this theme.
	 * 
	 * @return string
	 */
	public function getFolder() 
	{
		$folder = strtolower($this->name);
		$folder = preg_replace('/[^\da-z]/i', ' ', $folder);
		return preg_replace('/\s+/', '_', $folder);
	}
	
	public function getLayoutName()
	{
		$layout_name = strtolower($this->name);
		$layout_name = ucwords($layout_name);
		return 'layout/' . preg_replace('/\s/', '', $layout_name);
	}
	
	
}