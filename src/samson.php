<?php
/**
 * SamsonPHP initialization class
 */

//[PHPCOMPRESSOR(remove,start)]
// Subscribe to core started event to load all possible module configurations
\samson\core\Event::subscribe('core.configure', array(new \samsonos\config\Manager(), 'init'));
//[PHPCOMPRESSOR(remove,end)]