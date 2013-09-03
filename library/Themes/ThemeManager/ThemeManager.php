<?php
namespace Themes\ThemeManager;





use Zend\EventManager\EventManager;

use Zend\ModuleManager\Listener\ListenerOptions;

use Themes\ModuleManager\Listener\ThemeListener;

use Zend\Mvc\MvcEvent;

use Themes\ModuleManager\Feature\ThemeProviderInterface;

use Zend\ModuleManager\ModuleEvent;

use Themes\ThemeManager\Theme;

use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class ThemeManager implements 
	//EventManagerInterface, 
	ServiceManagerAwareInterface 
{
	
	/**
     * @var Zend\EventManager\EventManagerInterface
     */
    protected $events;
    
	/**
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;
    
    /**
     * The loaded theme instance.
     * 
     * @var Themes\ThemeManager\Theme
     */
    protected $theme;
    
    public function __construct(ServiceManager $serviceManager)
    {
		$this->serviceManager = $serviceManager;    
		
		$events = $serviceManager->get('ModuleManager')->getEventManager();
        $events->attach(ModuleEvent::EVENT_LOAD_MODULE, new ThemeListener(null));
    }
    
    
	/**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
    
	/**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_called_class());
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
        $this->events = $events;
        return $this;
    }
    
	/**
     * Retrieve service manager instance
     *
     * @return Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param Zend\ServiceManager\ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
    
    /**
     * Gets the default theme.
     * @return Theme
     */
    public function getDefaultTheme()
    {
    	// TODO: Default theme should come from config
    	$theme = new Theme();
    	$theme->populate(array(
    		'name'        => 'default',
    		'description' => 'Default theme'
    	));
    	
    	return $theme;
    }
    
    /**
     * Gets the loaded theme.
     * 
     * @param string $name
     */
    public function getTheme($name = null)
    {
    	if (empty($name)) return $this->getDefaultTheme();
    	
    	// TODO: Get configured theme
    	return $this->getDefaultTheme();
    }
    
}