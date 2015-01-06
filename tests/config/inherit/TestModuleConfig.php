<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 05.01.2015
 * Time: 22:29
 */
namespace project\inherit;

class TestModuleConfig extends \project\dev\TestModuleConfig
{
    public $parameterInt = 3;
    public $parameterArray = array('inherit' => 3);
}
