<?php
namespace samsonos\config;

use samson\core\Event;

 /**
 * Generic SamsonPHP core configuration system
 * @author Vitaly Egorov <egorov@samsonos.com>
 * @copyright 2014 SamsonOS
 */
class Scheme
{
    /** Global/Default scheme marker */
    const BASE = 'global';

    /** Entity configuration file pattern */
    const ENTITY_PATTERN = '*Config.php';

    /** @var Scheme[] Collection of available schemes */
    public static $schemes = array();

    /**
     * Initialize all configuration logic
     * @param string $basePath Path to configuration base folder
     */
    public static function init($basePath)
    {
        // Create global scheme instance
        self::$schemes[] = new Scheme($basePath, self::BASE);

        // Read all directories in base configuration path
        foreach (glob($basePath . '*', GLOB_ONLYDIR) as $environment) {
            // Create new configuration scheme
            self::$schemes[] = new Scheme($environment.'/', basename($environment));
        }
    }

    /** @var string Current configuration environment */
    protected $environment;

    /** @var string Configuration folder path */
    protected $path;

    /** @var array Collection of module identifier => configurator class */
    protected $entities;

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
     * @param string $path    Base path to configuration root folder
     * @param string $environment Configuration environment name
     */
    public function __construct($path, $environment)
    {
        // Store current configuration environment
        $this->environment = $environment;

        // Build path to environment configuration folder
        $this->path = $path;

        // Check scheme folder existence
        if (file_exists($this->path)) {
            // Load scheme entities
            $this->load();
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
        // Collection loaded entity classes
        $entityFiles = array();

        // Fill array of entity files with keys of file names without extension
        foreach (glob($this->path . self::ENTITY_PATTERN) as $file) {
            $entityFiles[basename($file, '.php')] = $file;
        }

        // Iterate all files in configuration folder path
        foreach ($entityFiles as $configFile) {
            // Register configuration class in system
            require_once($configFile);
        }

        // At this point we consider that all configuration classes for this environment has been required

        // Iterate all declared classes
        foreach ($entityFiles as $class => $file) {
            // If this class is Configurator ancestor
            if (is_a($class, __NAMESPACE__.'\Entity')) {
                // Get lowercase module name, removing "config" keyword
                $moduleId = str_replace('config', '', strtolower($class));

                // Store module identifier - configurator class name
                $this->entities[$moduleId] = $class;
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
