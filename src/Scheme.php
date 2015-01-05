<?php
namespace samsonos\config;

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
        self::create($basePath, self::BASE);

        // Read all directories in base configuration path
        foreach (glob($basePath . '*', GLOB_ONLYDIR) as $path) {
            // Create new configuration scheme
            self::create($path);
        }
    }

    /**
     * Create configuration scheme
     * @param string $path Path to configuration scheme folder
     * @param string $environment Configuration scheme environment identifier
     */
    public static function create($path, $environment = null)
    {
        // If no environment identifier is passed - use it from path
        $environment = !isset($environment) ? basename($path) : $environment;

        // Check if have NOT already created configuration for this environment
        if (!isset(self::$schemes[$environment])) {
            self::$schemes[$environment] = new Scheme($path . '/', $environment);
        }
    }

    /** @var string Current configuration environment */
    protected $environment;

    /** @var string Configuration folder path */
    protected $path;

    /** @var array Collection of module identifier => configurator class */
    public $entities = array();

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
        // Fill array of entity files with keys of file names without extension
        foreach (glob($this->path . self::ENTITY_PATTERN) as $file) {
            // Store loaded classes
            $classes = get_declared_classes();

            // Load entity configuration file
            require_once($file);

            // Get last loaded class name
            $loadedClasses = array_diff(get_declared_classes(), $classes);
            $class = end($loadedClasses);

            // If this is a entity configuration class ancestor
            if (is_subclass_of($class, __NAMESPACE__.'\Entity')) {
                // Store module identifier - entity configuration object
                $this->entities[$this->identifier($class)] = new $class();
            }
        }
    }

    /**
     * Convert entity configuration or object class name to identifier
     * @param string $class Entity configuration class name
     * @return string Entity real class name
     */
    public function identifier($class)
    {
        // TODO: Consider adding namespaces?
        $class = substr($class, strrpos($class, '\\') + 1);
        return str_replace('config', '', strtolower($class));
    }

    /**
     * Configure object with configuration entity parameters.
     *
     * If now $identifier is passed - automatic identifier generation
     * will take place from object class name.
     *
     * If additional parameters key=>value collection is passed, they
     * will be used to configure object instead of entity configuration
     * class.
     *
     * @param mixed $object Object for configuration with entity
     * @param string $identifier Configuration entity name
     * @param array|null $params Collection of configuration parameters
     */
    public function implement($object, $identifier = null, $params = null)
    {
        // If no entity identifier is passed get it from object class
        $identifier = isset($identifier) ? $identifier : $this->identifier(get_class($object));

        /** @var Entity $pointer Pointer to entity instance */
        $pointer = & $this->entities[$identifier];

        // If we have this entity configuration
        if (isset($pointer)) {
            // Implement entity configuration to object
            $pointer->implement($object, $params);
        }
    }
}
