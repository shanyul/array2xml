<h1 align="center"> array2xml </h1>

<p align="center"> Array to Xml tool.</p>


## Installing

```shell
$ composer require shanyuliang/array2xml -vvv
```

## Usage

1. normal

```php
use Shanyuliang\Array2xml\Array2xml;

$arr = [
    'aaa' => [
        'bbb' => 'ccc'
    ]
];
$array2xml = new Array2xml();

$result = $array2xml->generate($arr);
```
result


```php
{
    'code'  => 0,
    'errer' => '',
    'data'  => '<?xml version="1.0" encoding="utf-8"?>
                <aaa>
                    <bbb>ccc</bbb>
                </aaa>'
}
```

2. Add attribute and cdata for key

```php
use Shanyuliang\Array2xml\Array2xml;

$arr = [
    'aaa' => [
        'bbb' => 'ccc',
        'eee' => 'fff'
    ]
];

//add charset and version, default charset: utf-8 version 1.0
$array2xml = new Array2xml('utf-8', '1.0');

//add attribute for key
$attribute = [
    'aaa' => [
        'xmlns' => 'https://www.shanyuliang.com',
        'date'  => '2019-8-31'
    ]
];

//add cdata for key
$cdata = ['bbb'];

$result = $array2xml->generate($arr, $attribute, $cdata);
```
result

```php
{
    'code'  => 0,
    'errer' => '',
    'data'  => '<?xml version="1.0" encoding="utf-8"?>
                <aaa xmlns="https://www.shanyuliang.com" date="2019-8-31">
                    <bbb><![CDATA[ccc]]></bbb>
                    <eee>fff</eee>
                </aaa>'
}
```

3.multiple list

```php
use Shanyuliang\Array2xml\Array2xml;

$arr = [
    'xmlHead'   => [
        'A' => 'aaa',
        'B' => 'bbb',
        'C' => [
            'Aa' => '111',
            'Ab' => '222'
        ],
        'D|1'   => [
            'D1'    => 'd1'
        ],
        'D|2'   => [
            'D2'    => 'd2'
        ]
    ]
];

$array2xml = new Array2xml();

$result = $array2xml->generate($arr);
```
result

```php
{
    'code'  => 0,
    'errer' => '',
    'data'  => '<?xml version="1.0" encoding="utf-8"?>
                <xmlHead>
                    <A>aaa</A>
                    <B>bbb</B>
                    <C>
                        <Aa>111</Aa>
                        <Ab>222</Ab>
                    </C>
                    <D>
                        <D1>d1</D1>
                    </D>
                    <D>
                        <D2>d2</D2>
                    </D>
                </xmlHead>'
}
```


## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/shanyuliang/array2xml/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/shanyuliang/array2xml/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT