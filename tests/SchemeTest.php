<?php
namespace tests;

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 04.08.14 at 16:42
 */
class MainTest extends \PHPUnit_Framework_TestCase
{
    /** Tests init */
    public function setUp()
    {
        // Init configuration schemes
        \samsonos\config\Scheme::init(__DIR__.'/config/');
    }

    /** Test Init */
    public function testInit()
    {
        $this->assertArrayHasKey('global', \samsonos\config\Scheme::$schemes);
        $this->assertArrayHasKey('deploy', \samsonos\config\Scheme::$schemes);
        $this->assertArrayHasKey('dev', \samsonos\config\Scheme::$schemes);
    }
}
