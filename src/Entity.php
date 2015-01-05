<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 15.09.14 at 11:01
 */
namespace samsonos\config;

/**
 * Generic object configuration class
 * @author Vitaly Egorov <egorov@samsonos.com>
 * @copyright 2014 SamsonOS
 */
class Entity
{
    /** @var mixed Pointer to object instance to be configured */
    public $entity;

    /** @var array Key => value entity configuration parameters */
    public $params;

    /**
     * Constructor
     * @param object $entity Pointer to configured module
     */
    public function __construct(& $entity = null)
    {
        // Store entity pointer
        $this->entity = & $entity;

        // Get all configuration entity variables
        $variables = get_object_vars($this);

        // If any variables available
        if (sizeof($variables) && isset($entity)) {
            // Build variables collection, clear empty variables
            $this->params = array_filter(
                // Get only nested class variables array
                array_diff(
                    $variables,
                    get_class_vars(__CLASS__)
                )
            );

            // Iterate all children class variables
            foreach ($this->params as $var => $value) {
                // If module has configured property defined
                if (property_exists($entity, $var)) {
                    // Set module variable value
                    $entity->$var = $value;
                }
            }
        }
    }
}
