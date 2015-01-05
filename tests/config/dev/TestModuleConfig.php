<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 05.01.2015
 * Time: 22:29
 */
namespace project;

class TestModuleConfig extends \samsonos\config\Entity
{
    public $parameterString = '1';
    public $parameterInt = 2;
    public $parameterArray = array(3, '4');
}
