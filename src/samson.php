<?php
/**
 * SamsonPHP initialization class
 */

//[PHPCOMPRESSOR(remove,start)]
// Subscribe to core started event to load all possible module configurations
\samson\core\Event::subscribe('core.created', array('\samsonos\config\Scheme', 'init'));

// Subscribe to core module configure event
\samson\core\Event::subscribe('core.module.configure', array('\samsonos\config\Scheme', 'configure'));
//[PHPCOMPRESSOR(remove,end)]