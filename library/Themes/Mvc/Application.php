<?php
namespace Themes\Mvc;

use Themes\Mvc\Listener\ThemeListener;

use Themes\ThemeManager\AssetManager;

use Zend\ModuleManager\ModuleEvent;

use Zend\Mvc\Service\ServiceManagerConfig;

use Zend\ServiceManager\ServiceManager;

use Themes\ThemeManager\ThemeManager;

use Zend\Mvc\Application as ZendApplication;

class Application extends ZendApplication
{

	/**
     * Static method for quick and easy initialization of the Application.
     *
     * If you use this init() method, you cannot specify a service with the
     * name of 'ApplicationConfig' in your service manager config. This name is
     * reserved to hold the array from application.config.php.
     *
     * The following services can only be overridden from application.config.php:
     *
     * - ModuleManager
     * - SharedEventManager
     * - EventManager & Zend\EventManager\EventManagerInterface
     * - ThemeManager (NEW)
     *
     * All other services are configured after module loading, thus can be
     * overridden by modules.
     *
     * @param array $configuration
     * @return Application
     */
    public static function init($configuration = array())
    {
    	$smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : array();
        $listeners = isset($configuration['listeners']) ? $configuration['listeners'] : array();
        
        $serviceManager = new ServiceManager(new ServiceManagerConfig($smConfig));
        $serviceManager->setService('ApplicationConfig', $configuration);
        // Load themes manager
        $themeManager = new ThemeManager($serviceManager);
        $serviceManager->setService('ThemeManager', $themeManager);
        // Load theme listener
        $themeListener = new ThemeListener();
        $themeListener->setServiceManager($serviceManager);
        $serviceManager->setService('ThemeListener', $themeListener);
        $listeners[] = 'ThemeListener';
        // End themes manager
        $serviceManager->get('ModuleManager')->loadModules();
        return $serviceManager->get('Application')->bootstrap($listeners);
    }
    
	
    
}