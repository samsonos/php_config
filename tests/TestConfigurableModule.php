<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 05.01.2015
 * Time: 23:01
 */
namespace tests;

use samsonos\config\Entity;

class TestConfigurableModule implements \samsonos\config\IConfigurable
{
    public $parameterString = '5';
    public $parameterInt = 5;
    public $parameterArray = array('test' => '4');

    /**
     * @param Entity $entityConfiguration current instance for configuration
     * @return boolean False if something went wrong otherwise true
     */
    public function configure(Entity $entityConfiguration)
    {
        var_dump($this);
        $this->parameterArray['configurable'] = 'custom configure implementation';
    }
}