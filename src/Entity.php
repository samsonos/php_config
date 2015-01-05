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
    /** @var array Key => value entity configuration parameters */
    public $params;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Get all configuration entity variables
        $variables = get_object_vars($this);

        // If any variables available
        if (sizeof($variables)) {
            // Build variables collection, clear empty variables
            $this->params = array_filter(
                // Get only nested class variables array
                array_diff(
                    $variables,
                    get_class_vars(__CLASS__)
                )
            );
        }
    }

    /**
     * Configure object with configuration entity parameters.
     * If additional parameters key=>value collection is passed, they
     * will be used to configure object.
     *
     * @param mixed $object
     */
    public function implement(& $object, $params = null)
    {
        // Use entity params if external is not passed, iterate all children class variables
        foreach (isset($params) ? $params : $this->params as $var => $value) {
            // If module has configured property defined
            if (property_exists($object, $var)) {
                // Set module variable value
                $object->$var = $value;
            }
        }
    }
}
