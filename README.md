# PhpSpamFilter

This can use as TextFilter, SpamFilter, Keywords Filter
- You can set default keywords and urls in SpamFilter.php filte.

### Examples
```php
SpamFilter::result('Hey do you know sex bit.ly?');

$data = SpamFilter::setData('keywords', array('naywin'))
 	    ->setData('urls', array('github.com'))
 	    ->resultWithValue('Heynaywin do you know sex women github.com with s in bit.ly?');
      
beautiful_output($data);
```
