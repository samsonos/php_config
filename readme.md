#SamsonPHP Configuration system

OOP based configuration system. This approach uses all abilities of PHP OOP for creating configurations based on classes.  
 
[![Latest Stable Version](https://poser.pugx.org/samsonos/php_config/v/stable.svg)](https://packagist.org/packages/samsonos/php_config) 
[![Build Status](https://travis-ci.org/samsonos/php_config.png)](https://travis-ci.org/samsonos/php_config) 
[![Code Coverage](https://scrutinizer-ci.com/g/samsonos/php_config/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/samsonos/php_config/?branch=master)
[![Code Climate](https://codeclimate.com/github/samsonos/php_config/badges/gpa.svg)](https://codeclimate.com/github/samsonos/php_config) 
[![Total Downloads](https://poser.pugx.org/samsonos/php_config/downloads.svg)](https://packagist.org/packages/samsonos/php_config)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/samsonos/php_config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/samsonos/php_config/?branch=master)

## Configuration scheme
Your project can have any amount of possible configurations, which is usually used for different environments, such as *development, test, deploy, production* stages,
for this purposes we have created ```samsonos\config\Scheme```, each of them corresponds to specific environment. In practice you should have _base configuration folder_
by default it located at ```app/config``` folder. 

### Global configuration 
In root of your ```app/config``` folder you should create your default entity configuration classes. If no current configuration environment would be specified, this
entity configuration classes would be used automatically.

### Creating configuration environment
To create new configuration environment you should create new folder in your _base configuration folder_(by default it located at ```app/config```), for example we 
want to create ```production``` environment, new folder path would be: ```app/config/production/```. And all entity configuration classes there would correspond
to your ```production``` configuration scheme, which will be created automatically.

##Entity configuration
To configure your project modules\objects  - you should use classes, for correct finding this classes among others, we force you to extend our base entity configuration class - 
```samsonos\config\Entity```:
```php
namespace project;

class entityIDConfig extends samsonos\config\Entity
{
    public $parameter = 'value';
}
```

Your entity configuration class name should meet next logic: ```[EntityIdentifier]Config```:
* ```EntityIdentifier``` - configured module/object identifier  
* All entity configuration class names must end with ```Config```

Your entity configuration class namespace should meet next logic:
* Every class has to have any name space defined(by default your project name)
* Global entity configuration classes(located at [base configuration folder](#configuration-scheme)), should
have name space defined in previous list item.
* Other environments entity configuration classes (located at [other inner folders](#creating-configuration-environment)),
should have namespace: [ParentNamespace]\[EnvironmentName], if we extend our previous entity configuration example, but now
for ```production``` environment:
```php
namespace project\production;

class entityIDConfig extends samsonos\config\Entity
{
    public $parameter = 'value';
}
```

> IMPORTANT! As we use PSR-* standard - Class name must match file name


