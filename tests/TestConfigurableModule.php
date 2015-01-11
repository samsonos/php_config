<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 05.01.2015
 * Time: 23:01
 */
namespace tests;

class TestConfigurableModule
{
    public $parameterString = '5';
    public $parameterInt = 5;
    public $parameterArray = array('test' => '4');

    /**
     * @param mixed $entityConfiguration current instance for configuration
     * @return boolean False if something went wrong otherwise true
     */
    public function configure($entityConfiguration)
    {
        $this->parameterArray['configurable'] = 'custom configure implementation';
        $this->parameterArray = array_merge($this->parameterArray, (array)$entityConfiguration);
    }
}
