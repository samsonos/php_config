<?php
namespace tests;

use samsonos\config\Manager;
use samsonos\config\Scheme;

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 04.08.14 at 16:42
 *
 * IMPORTANT:
 * As our configuration system based on classes and class loading
 * we cannot use standard approach with setUp() as when we will call second test
 * (second call to setUp()) all configuration classes would already be loaded and
 * thought configuration manager schemes would be empty, this why we use static
 * test init method setUpBeforeClass() to create one static configuration manager instance
 * and them use it as reference in all further tests.
 */
class SchemeTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \samsonos\config\Manager */
    protected static $staticManager;

    /** @var  \samsonos\config\Manager */
    protected $manager;

    /** @var  Scheme */
    protected $globalScheme;

    public static function setUpBeforeClass()
    {
        // Init configuration schemes
        self::$staticManager = new Manager();
        self::$staticManager->init(__DIR__ . '/config/');
    }

    /** Tests init */
    public function setUp()
    {
        $this->manager = & self::$staticManager;
        //var_dump($this->manager->schemes);

        // Get default scheme
        $this->globalScheme = & $this->manager->schemes[Scheme::BASE];

        // Import object for testing
        require_once 'TestModule.php';
    }

    /** Test Init */
    public function testInit()
    {
        $this->assertArrayHasKey('global', $this->manager->schemes);
        $this->assertArrayHasKey('deploy', $this->manager->schemes);
        $this->assertArrayHasKey('dev', $this->manager->schemes);
    }

    /** Test implement*/
    public function testImplementGlobal()
    {
        // Create object for configuration
        $object = new TestModule();

        // Configure object
        $this->manager->configure($object, 'testmodule');

        $this->assertEquals('1', $object->parameterInt);
        $this->assertEquals('1', $object->parameterString);
        $this->assertArrayHasKey('global', $object->parameterArray);
    }

    /** Test implement*/
    public function testImplementDev()
    {
        // Create object for configuration
        $object = new TestModule();

        // Switch to deploy configuration scheme
        $this->manager->change('dev');

        // Configure object
        $this->manager->configure($object, 'testmodule');

        $this->assertEquals('2', $object->parameterInt);
        $this->assertEquals('2', $object->parameterString);
        $this->assertArrayHasKey('dev', $object->parameterArray);
    }

    /** Test implement*/
    public function testImplementInherit()
    {
        // Create object for configuration
        $object = new TestModule();

        // Switch to deploy configuration scheme
        $this->manager->change('inherit');

        // Configure object
        $this->manager->configure($object, 'testmodule');

        $this->assertEquals('3', $object->parameterInt);
        $this->assertEquals('2', $object->parameterString);
        $this->assertArrayHasKey('inherit', $object->parameterArray);
    }

    /** Test implement not found - use global */
    public function testNotFoundUseGlobal()
    {
        // Create object for configuration
        $object = new TestModule();

        // Switch to deploy configuration scheme
        $this->manager->change('deploy');

        // Configure object
        $this->manager->configure($object, 'testmodule');

        $this->assertEquals('1', $object->parameterInt);
        $this->assertEquals('1', $object->parameterString);
        $this->assertArrayHasKey('global', $object->parameterArray);
    }
}
