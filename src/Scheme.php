<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 15.09.14 at 12:10
 */
 namespace samsonos\config;

 use samson\core\Event;
 use samson\core\File;

 /**
 * Generic SamsonPHP core configuration system
 * @author Vitaly Egorov <egorov@samsonos.com>
 * @copyright 2014 SamsonOS
 */
class Scheme
{
    /** Global/Default scheme marker */
    const BASE = 'global';

    /** @var Scheme[] Collection of available schemes */
    public static $schemes = array();

    /**
     * Initialize all configuration logic
     * @param string Path to configuration base folder
     */
    public static function init($basePath)
    {
        elapsed('Init configuration system');

        // Create global scheme instance
        self::$schemes[] = new Scheme($basePath, self::BASE);

        // Read all directories in base configuration path
        foreach(glob($basePath . '/*' , GLOB_ONLYDIR) as $environment) {
            trace($environment);
            //self::$schemes[] = new Scheme();
        }
    }

    /** @var string Current configuration environment */
    public $environment;

    /** @var string Configuration folder path */
    public $path;

    /** @var array Collection of module identifier => configurator class */
    public $configurators;

    /**
     * Create configuration instance.
     *
     * All module configurators must be stored within configuration base path,
     * by default this is stored in __SAMSON_CONFIG_PATH constant.
     *
     * Every environment configuration must be stored in sub-folder with the name of this
     * environment within base configuration folder.
     *
     * Configurators located at base root configuration folder considered as generic
     * module configurators.
     *
     * @param string $basePath    Base path to configuration root folder
     * @param string $environment Configuration environment name
     */
    public function __construct($path, $environment)
    {
        // Store current configuration environment
        $this->environment = $environment;

        // Build path to environment configuration folder
        $this->path = $basePath.'/'.(isset($environment) ? $environment.'/' : '');

        if (file_exists($this->path)) {
            return e('Environment(##) configuration path(##) does not exists', E_SAMSON_CORE_ERROR, array($environment, $this->path));
        }
    }

    /**
     * Load configuration for this environment.
     *
     * All module configurator files must end with "Config.php" to be
     * loaded.
     */
    public function load()
    {
        // Iterate all files in configuration folder path
        foreach(File::dir($this->path) as $configFile) {
            // Match only files ending with ...Config.php
            if(stripos($configFile, 'Config.php') !== false) {
                // Register configuration class in system
                require_once($configFile);
            }
        }

        // At this point we consider that all configuration classes for this environment has been required

        // Iterate all declared classes
        foreach (get_declared_classes() as $class) {
            // If this class is Configurator ancestor
            if (is_subclass_of($class, __NAMESPACE__.'\Configurator')) {
                // Get lowercase module name, removing "config" keyword
                $moduleId = str_replace('config', '', strtolower($class));

                // Store module identifier - configurator class name
                $this->configurators[$moduleId] = $class;
            }
        }
    }
}

//[PHPCOMPRESSOR(remove,start)]
// Subscribe to core started event for initializing configuration system
Event::subscribe('core.created', array(__NAMESPACE__.'\Scheme', 'init'));

// Subscribe to core started event to load all possible module configurations
//Event::subscribe('core.routing', array('\samson\core\Config', 'init'));

// Subscribe to core module loaded core event
//Event::subscribe('core.module_loaded', array('\samson\core\Config', 'implement'));
//[PHPCOMPRESSOR(remove,end)]
 