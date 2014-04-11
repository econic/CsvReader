CsvReader
=========

CsvReader is a **unit-tested** PHP **composer package** with a utility class that reads your csv data and converts it into an associative two-dimensional array.
Everything is configurable and many options and even modifiers can do the work for you...

## License
CsvReader is released under the MIT license

## Usage
Usage couldn't be simpler: just set the source, configure your options & get the result.

### minimum configuration

```php
$CsvReader = new \Econic\CsvReader\Reader();

$array = $CsvReader->setSource("Elephant,421,86\nMouse,15,4")->parse();
```

### result
```php
array(
	0 => array(
		0 => "Elephant"
		1 => 421
		2 => 86
	),
	1 => array(
		0 => "Mouse"
		1 => 15
		2 => 4
	)
)
```

With this you can easily iterate over your result:

```php
foreach ($result as $line) {
	echo "A " . $line[0] . " can become " . $line[1] . "cm tall and " . $line[2] . " years old.";
}
```

Or even more convenient, when you set a key for your values (see below to learn how):

```php
foreach ($result as $line) {
	echo "A " . $line["title"] . " can become " . $line["size"] . "cm tall and " . $line["age"] . " years old.";
}
```

## Characters
CSVreader has plenty of options to offer.
You don't have to use any of them and you can use all of them at once if you want. Just as you like.
Just use the respective setters/adders on the CSVreader object.

```php
$CsvReader->setMyFancyOption($value);
```

All setters/adders return the CSVreader object for easy chaining.

```php
$CsvReader->setOption1($value1)->setOption2($value2)->setOption3...
```

### source
The source given as string. It contains the CSV data you want to get parsed.

#####Type: String
#####Default: ''
```php
$CsvReader->setSource("1,2,3\n4,5,6");
```

### delimiter
The delimiter in the csv data. Want a semicolon here? No problem...

#####Type: String
#####Default: ','
```php
$CsvReader->setDelimiter(";");
```

### newline
The newline character in the csv data. You don't break lines? Ok then... how about a dash?

#####Type: String
#####Default: "\n"
```php
$CsvReader->setNewline("-");
```

### enclosure
The enclosure character in the csv data. Useful when your values contain the delimiter or the newline character. Just wrap your whole value with the enclosure caracter.

#####Type: String
#####Default: '"'
```php
$CsvReader->setEnclosure("'");
```

### escape
The escape character in the csv data. Ok now you have " as enclosure character but your value contains a " ... Just prefix it with the escape character!

#####Type: String
#####Default: '\'
```php
$CsvReader->setEscape("!");
```

## Options
You can configure even more with CSVreader, like the keys, modifiers, a global trim, ...

### trim
If the values should be trimmed after parsing. Turned on by default.
Will be executed before the modifiers.

#####Type: Boolean
#####Default: true
```php
$CsvReader->setTrim(false);
```
### keys
Change the keys if you want to access the values via their respective attribute names.

#####Type1: Integer, Type2: String
```php
$CsvReader->setKey(0, "title");
```

Add multiple keys at once like so

#####Type: Array
```php
$CsvReader->addKeys( array( 0 => "title", 1 => "size" ) );
```

Reset the keys whenever necessary

```php
$CsvReader->resetKeys();
```

### modifiers
Let's say the first value in every line of your csv is a title. It saved like "-v-a-l-u-e-" but your result should be like "VALUE".
Just add a modifier at your chosen key and let the reader to its work. First parameter is the position of your value (0 based) or, if you chose another key for the value, your key.

#####Type1: Integer/String, Type2: Callable
```php
$CsvReader->addModifier(0, function($var){
	return strtoupper(str_replace("-", "", $var));
});
```

Done. Every value will have its dashes removed and converted to uppercase. Of course you can do extremely intense stuff here.
And you can stack the modifiers. As many as you want. They will be executed in the order you added them.

```php
$CsvReader->addModifier("title", function($var){
	return str_replace("-", "", $var);
})->addModifier("title", function($var){
	return strtoupper($var);
});
```

If you want to remove the modifiers after you added them, you can reset the modifiers of one key or all modifiers. Use

```php
$CsvReader->resetModifiers();
```

to remove all modifiers or

```php
$CsvReader->resetModifiers("title");
```

to remove just the modifiers registered for the title value.