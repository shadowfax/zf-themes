<?php
namespace Themes\ThemeManager;

class Theme
{
	protected $name;
	protected $description;
	
	protected $layoutName;
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Gets the folder name.
	 * 
	 * The folder name is the same as the theme name but 
	 * lowercase and spaces are changed with underscores.
	 * 
	 * For example:
	 * 'Default theme' is converted to 'default_theme'
	 * 
	 * @return string
	 */
	public function getFolder()
	{
		$folder = strtolower($this->name);
		//$folder = ucwords($folder);
		return preg_replace('/\s/', '_', $folder);
	}
	
	public function getLayoutName()
	{
		if (null === $this->layoutName) {
			$layout_name = strtolower($this->name);
			$layout_name = ucwords($layout_name);
			$this->layoutName = preg_replace('/\s/', '', $layout_name);
		}
		
		return 'layout/' . $this->layoutName;
	}
	
	public function populate($data)
	{
		$this->name        = $data['name'];
		$this->description = $data['description'];
		return $this;
	}
}