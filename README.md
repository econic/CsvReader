CSVreader
=========

CSVreader is a PHP utility class that reads your csv data and converts it into an associative two-dimensional array.
Everything is configurable and many options such as modifiers can do the work for you...

## Usage
Usage couldn't be simpler: just set the source, configure your options & get the result.

### minimum configuration

```html
$CSVreader = new \econic\CSVreader\CSVreader();

$array = $CSVreader->setSource("1,2,3\n4,5,6")->parse();
```

### result
```php
array(
	0 => array(
		0 => 1
		1 => 2
		2 => 3
	),
	0 => array(
		0 => 4
		1 => 5
		2 => 6
	)
)
```