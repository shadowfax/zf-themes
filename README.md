ZF-Themes
=========

Zend Framework 2 (ZF2) theme manager

Add the following line to composer.json of your application

    "require": {
        "shadowfax/zf-themes": "dev-master"
    }

In the index.php change 

    Zend\Mvc\Application::init(require 'config/application.config.php')->run();

with

    Themes\Mvc\Application::init(require 'config/application.config.php')->run();
    
This will load the `ThemeManager` service.


When creating a new module simply implement `Themes\ModuleManager\Feature\ThemeProviderInterface`

    ...
    
    use Themes\ModuleManager\Feature\ThemeProviderInterface;
    
    ...
    
    class Module implements ThemeProviderInterface
    {
    
        ...
        
        public function getDir()
        {
        	return __DIR__;
        }
        
        public function getNamespace()
        {
        	return __NAMESPACE__;
        }
        
        ...
        
    }
    
The themes should be in a subfolder called `themes` inside the module directory. 
There should be, atleast, a theme called `default`. 

Example tree:
+ themes
  + default
  + my_new_theme 
  
The layout file `layout.phtml` should be in the root of the theme, all the rest 
works just like Zend Framework's `view` folder.

Assets (CSS, JS, Images,...) can be stored in the `assets` folder under the theme.
Here is an example entry for the default module.


+ default
  + assets
    + css
      + layout.css
      + bootstrap.css
    + js
      + jquery.js
  + application
    + index
      + index.phtml
  + layout.phtml

The module makes a route named `assets`. The url to assets is

    /assets/:modules/*
    
Where `*` is the relative path to the actual asset.

Making a port of a Zend Framework 2 application to a theme based applications
should be a breeze as you only have to change the name of `view` forlder to `default`, 
move it into a folder named `themes` and move the layout file and you are ready.  
