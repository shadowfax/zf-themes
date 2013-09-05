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
namespace Themes\Mvc\Listener;

use Zend\Http\Response;

use Zend\Mvc\Router\RouteMatch;

use Zend\ServiceManager\ServiceManager;

use Zend\ServiceManager\ServiceManagerAwareInterface;

use Zend\Mvc\Router\Http\Segment;

use Zend\Mvc\Router\Http\Literal;

use Zend\EventManager\EventManagerInterface;

use Zend\Mvc\MvcEvent;

use Zend\EventManager\ListenerAggregateInterface;

class ThemeListener implements 
	ListenerAggregateInterface, 
	ServiceManagerAwareInterface
{
	protected $serviceManager;
	
	protected $listeners = array();
	
	protected $themeManager;
 
	/**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, array($this, 'onBootstrap'));
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -1000);
    }
 
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
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
    
    public function onBootstrap(MvcEvent $e)
    {
    	// TODO: INIT THEMES HERE
    	
    	// Build the assets route
    	$router = $e->getRouter();
    	
    	$router->addRoute('assets', array(
    		'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
            'route'    => '/assets',
            	'defaults' => array(),
            ),
            'may_terminate' => true,
            'child_routes' => array(
            	'module' => array(
                	'type'    => 'Segment',
                    'options' => array(
                    'route'   => '/[:module]',
                    	'constraints' => array(
                        	'module' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults' => array(),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
		            	'wildcard' => array(
		                	'type'    => 'Wildcard',
		                ),
		            ),
                ),
            ),
    	));
    	
    }
    
    /**
     * Takes care of assets inside the module template.
     * 
     * TODO: Cache?
     * 
     * @param MvcEvent $e
     */
    public function onRoute(MvcEvent $e)
    {
    	$match = $e->getRouteMatch();
    	if (!$match instanceof RouteMatch) return;
    	
    	$route_name = $match->getMatchedRouteName();
    	if (strcasecmp($route_name, 'assets/module/wildcard') === 0) {
    		$moduleManager = $this->serviceManager->get('ModuleManager');
    		
    		// Check the supplied module is valid
    		$modules = $moduleManager->getModules();
    		$modules_keys  = array_map('strtolower', $modules);
    		$modules = array_combine($modules_keys, $modules);
    		unset($modules_keys);
    		
    		$module_name = $match->getParam('module', null);
    		if(!array_key_exists($module_name, $modules)) return;
    		
    		$module = $moduleManager->getModule($modules[$module_name]);
    		unset($modules);
    		
    		$themeManager = $this->serviceManager->get('ThemeManager');
    		
    		$assets_path = $module->getDir() . DIRECTORY_SEPARATOR . $themeManager->getThemesFolder() . DIRECTORY_SEPARATOR . $themeManager->getTheme()->getFolder() . '/assets';
    		$assets_path = realpath($assets_path);
    		if (empty($assets_path)) {
    			return;
    		} elseif (!is_dir($assets_path)) {
    			return;
    		}
    		$assets_path = rtrim($assets_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    		
    		
    		// assemble the module route
    		$base_url = $e->getRouter()->assemble(array('module' => $module_name), array('name' => 'assets/module'));
    		$asset = $assets_path . substr($e->getRequest()->getRequestUri(), strlen($base_url));
    		$asset = realpath($asset);
    		if (empty($asset)) {
    			return;
    		} elseif (substr_compare($asset, $assets_path, 0, strlen($assets_path), false) !== 0) {
    			// Must be a directory transversal attack
    			return;
    		} elseif (!is_readable($asset)) {
    			return;
    		}
  
			$content = file_get_contents($asset);
			if (empty($content)) return;
			
			$mime_types = require_once(__DIR__ . '/data/MimeTypes.php');
			$extension = pathinfo($asset, PATHINFO_EXTENSION);
			$extension = strtolower($extension);
			if (array_key_exists($extension, $mime_types)) {
				$mime_type = $mime_types[$extension];
			} else {
				$mime_type = 'application/octet-stream';
			}
    		
    		
    		$response = $e->getResponse();
    		$response->setStatusCode(Response::STATUS_CODE_200);
    		$headers = $response->getHeaders();
			$headers->addHeaderLine('Content-Type', $mime_type);
			$response->setContent($content);
    		
    		return $response;
    	}
    }
}