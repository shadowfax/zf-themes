<?php
namespace Themes\ModuleManager\Feature;

use Zend\EventManager\EventInterface;

use Zend\ModuleManager\ModuleManager;

interface ThemeProviderInterface
{
	/**
	 * We need to know the directory of the module we are working with.
	 */
	public function getDir();
	/**
	 * We need to know the namespace of the module we are working with.
	 */
    public function getNamespace();
    
    
	//public function initTheme(EventInterface $e);
	
	//public function setTheme(ModuleManager $moduleManager); 
	
	
}