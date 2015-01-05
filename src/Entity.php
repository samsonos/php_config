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
    public function __construct(& $entity)
    {
        // Store entity pointer
        $this->entity = & $entity;

        // Build variables collection
        $this->params = array_filter( // Clear empty variables
            array_diff( // Get only nested class variables array
                get_object_vars($this),
                get_class_vars(__CLASS__)
            )
        );

        // Iterate all children class variables
        foreach($this->params as $var => $value) {
            // If module has configured property
            if (property_exists($entity, $var)) {
                // Set module variable value
                $entity->$var = $value;
            }
        }
    }
}
 