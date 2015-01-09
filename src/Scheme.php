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

    /** @var array Collection of file path -> class loaded */
    protected static $classes = array();

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

            // Try to get class from static collection
            $class = & self::$classes[$file];

            // If we have not already loaded this class before with other schemes
            if (!isset(self::$classes[$file])) {
                // Store loaded classes
                $classes = get_declared_classes();

                // Load entity configuration file
                require_once($file);

                // Get last loaded class name
                $loadedClasses = array_diff(get_declared_classes(), $classes);

                // Get loaded class - store class to static collection
                $class = end($loadedClasses);
            }

            // If this is a entity configuration class ancestor
            if (in_array(__NAMESPACE__.'\Entity', class_parents($class))) {
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
        // If namespace is present
        if (($classNamePos = strrpos($class, '\\')) !== false) {
            $class = substr($class, $classNamePos+1);
        }

        return str_replace('config', '', strtolower($class));
    }

    /**
     * Retrieve entity configuration by identifier.
     * If entity configuration not found null will be
     * returned.
     *
     * @param string $identifier Entity identifier
     * @return Entity|null Entity configuration pointer or null
     */
    public function & entity($identifier)
    {
        // Convert identifier of entity configuration name is passed
        $identifier = $this->identifier($identifier);

        // Return pointer
        return $this->entities[$identifier];
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
     *
     * @return boolean True if we have successfully configured object
     */
    public function configure(& $object, $identifier = null, $params = null)
    {
        // If no entity identifier is passed get it from object class
        $identifier = isset($identifier) ? $identifier : $this->identifier(get_class($object));

        /** @var Entity $pointer Pointer to entity instance */
        $pointer = $this->entity($identifier);

        // If we have found this entity configuration
        if (isset($pointer)) {
            // Implement entity configuration to object
            return $pointer->configure($object, $params);
        }

        // We have failed
        return false;
    }
}
