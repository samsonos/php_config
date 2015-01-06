<?php
namespace tests;

use samsonos\config\Scheme;

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 04.08.14 at 16:42
 */
class SchemeTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Scheme */
    protected $globalScheme;

    /** Tests init */
    public function setUp()
    {
        // Init configuration schemes
        Scheme::init(__DIR__.'/config/');

        // Get default scheme
        $this->globalScheme = Scheme::$schemes[Scheme::BASE];

        // Import object for testing
        require_once 'TestModule.php';
    }

    /** Test Init */
    public function testInit()
    {
        $this->assertArrayHasKey('global', Scheme::$schemes);
        $this->assertArrayHasKey('deploy', Scheme::$schemes);
        $this->assertArrayHasKey('dev', Scheme::$schemes);
    }

    /** Test implement*/
    public function testImplementGlobal()
    {
        // Create object for configuration
        $object = new TestModule();

        // Configure object
        $this->globalScheme->configure($object, 'testmodule');

        $this->assertEquals('1', $object->parameterInt);
        $this->assertEquals('1', $object->parameterString);
        $this->assertArrayHasKey('global', $object->parameterArray);
    }

    /** Test implement*/
    public function testImplementDev()
    {
        // Create object for configuration
        $object = new TestModule();

        // Configure object
        Scheme::$schemes['dev']->configure($object, 'testmodule');

        $this->assertEquals('2', $object->parameterInt);
        $this->assertEquals('2', $object->parameterString);
        $this->assertArrayHasKey('dev', $object->parameterArray);
    }

    /** Test implement*/
    public function testImplementInherit()
    {
        // Create object for configuration
        $object = new TestModule();

        // Configure object
        Scheme::$schemes['inherit']->configure($object, 'testmodule');

        $this->assertEquals('3', $object->parameterInt);
        $this->assertEquals('2', $object->parameterString);
        $this->assertArrayHasKey('inherit', $object->parameterArray);
    }

    /** Test implement not found - use global */
    public function testImplementNotFound()
    {
        // Create object for configuration
        $object = new TestModule();

        // Configure object
        Scheme::$schemes['deploy']->configure($object, 'testmodule');

        $this->assertEquals('1', $object->parameterInt);
        $this->assertEquals('1', $object->parameterString);
        $this->assertArrayHasKey('global', $object->parameterArray);
    }
}
