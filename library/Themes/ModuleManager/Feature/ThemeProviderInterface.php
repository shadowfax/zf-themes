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