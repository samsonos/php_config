<?php
/**
 * SamsonPHP initialization class
 */

//[PHPCOMPRESSOR(remove,start)]
$configurator = new \samsonos\config\Manager();

// Subscribe to core started event to load all possible module configurations
\samson\core\Event::subscribe('core.configure', array($configurator, 'init'));
//[PHPCOMPRESSOR(remove,end)]