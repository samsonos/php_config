<?php
/**
 * SamsonPHP initialization class
 */

//[PHPCOMPRESSOR(remove,start)]
// Subscribe to core started event to load all possible module configurations
\samson\core\Event::subscribe('core.configure', array('\samsonos\config\Scheme', 'init'));
//[PHPCOMPRESSOR(remove,end)]