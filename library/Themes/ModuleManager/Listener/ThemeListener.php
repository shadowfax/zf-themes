<?php
namespace Themes\ModuleManager\Listener;

use Zend\Mvc\MvcEvent;

use Themes\ModuleManager\Feature\ThemeProviderInterface;

use Zend\ModuleManager\ModuleEvent;

use Zend\ModuleManager\Listener\AbstractListener;

class ThemeListener extends AbstractListener
{

	/**
     * @param ModuleEvent $e
     * @return void
     */
    public function __invoke(ModuleEvent $e)
    {
        $module = $e->getModule();
    	if ((!$module instanceof ThemeProviderInterface) && !method_exists($module, 'initTheme')) {
    		return;
    	}
    	
    	$moduleManager = $e->getTarget();
        $events        = $moduleManager->getEventManager();
        $sharedEvents  = $events->getSharedManager();
        
        $module_directory = $module->getDir();
        
        
        $sharedEvents->attach('Zend\Mvc\Application', MvcEvent::EVENT_BOOTSTRAP, function($e) use($module_directory) {
        	$application    = $e->getApplication();
    		$eventManager   = $application->getEventManager();
    		$serviceManager = $application->getServiceManager();
    		
        	$themeManager = $serviceManager->get('ThemeManager');
			$theme        = $themeManager->getTheme();
			$theme_folder = $theme->getFolder();
			
			$theme_path   = $module_directory . '/themes/' . $theme_folder;
			if (!is_dir($theme_path)) {
				$theme        = $themeManager->getDefaultTheme();
				$theme_folder = $theme->getFolder();
				if (!is_dir($theme_path)) {
					throw new MissingDefaultThemeException();
				}
			}
    		
	    	$theme_path = $module_directory . '/themes/' . $theme->getFolder();
		    	
	    	// Views
	    	// Add the folder to the template resolver.
			$templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack');   
			$templatePathResolver->addPath($theme_path);
	            
	    	// Layout
		   	if (file_exists($theme_path . '/layout.phtml')) {
		   		$templateMapResolver = $serviceManager->get('Zend\View\Resolver\TemplateMapResolver');
		   		$layout_name = $theme->getLayoutName();
		   		
		   		if (!$templateMapResolver->has($layout_name)) {
		   			$templateMapResolver->add($layout_name, $theme_path . '/layout.phtml');
		   		}
		   	}
        });
        
    	
        
        $sharedEvents->attach($module->getNamespace(), MvcEvent::EVENT_DISPATCH, function($e) use($module_directory) {
        	// Set the layout for this module if available
	        $serviceManager = $e->getApplication()->getServiceManager();
	            
			$themeManager = $serviceManager->get('ThemeManager');
			$theme        = $themeManager->getTheme();
			$theme_folder = $theme->getFolder();
				
			$theme_path   = $module_directory . '/themes/' . $theme_folder;
			if (!is_dir($theme_path)) {
				$theme        = $themeManager->getDefaultTheme();
				$theme_folder = $theme->getFolder();
				if (!is_dir($theme_path)) {
					throw new MissingDefaultThemeException();
				}
			}
	            
	        $layout_name = $theme->getLayoutName();
			$templateMapResolver = $serviceManager->get('Zend\View\Resolver\TemplateMapResolver');
			if ($templateMapResolver->has($layout_name)) {
				$viewModel = $e->getViewModel(); 
	        	$viewModel->setTemplate($layout_name);
			}
        }, 100);
    }
    
   
    
}