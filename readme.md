Batch allows you to separate code that is fetching data and processing them in batches. It has many uses.


# Example

Write ten lines of 80 characters per line.
```php
use Rixxi\Utils\Batch;

$batch = new Batch(function ($values) {
	echo implode($values), PHP_EOL;
}, $limit = 80);

$lines = 10;
for ($x = $limit * $lines; $x-- > 0;) {
	$batch[] = chr(ord('A') + rand(0, 26 /* ord('Z') - ord('A') */));
}
```


# Flushing & Callback

Batch is automatically flushed and reset when limit is reached and when is destroyed.

If **no items were added** to batch since start or last flush **callback is not called**.

You can flush batch manually at any point calling `flush()`.
```php
$batch->flush();
```
Or flush and throw away calling unset on batch.
```php
unset($batch);
```
Usually you don't have to unset since garbage collector will do it for you at the end of function.


# Install
```sh
composer require rixxi/utils
```


# Troubleshooting

If you are getting random errors after processing data via Batch make sure you flush it before doing anything else.
*You are probably assuming all data are flushed when there might be few left after last round.*

Ie.: in Doctrine when processing entities don't forget to flush manually before calling `$repository->clear();`.