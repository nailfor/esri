ESRI parser for php
========================

ESRI parser shapes and metadata

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require nailfor/esri "1.0"
```
or add

```json
"nailfor/esri" : "*"
```

to the require section of your application's `composer.json` file.

Usage
-----

```

use esri\shpParser;

$path = 'path/to/folder/or/file.shp';
$parser = new shpParser(['path'=>$path]);
$records = $parser->getRecords();

```

Credits
-------

- [nailfor](https://github.com/nailfor)

License
-------

The BSD License (BSD). Please see [License File](LICENSE.md) for more information.
