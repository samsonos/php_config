<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 05.01.2015
 * Time: 22:29
 */
namespace project;

class DependentTestModuleConfig extends TestModuleConfig
{
    public $parameterString = '111';
    public $parameterInt = 111;
    public $parameterArray = array('dependent'=>1, '1');
}
