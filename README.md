# Imaginary client
[![Latest Stable Version](https://poser.pugx.org/arjanwestdorp/imaginary-client/v/stable?format=flat-square)](https://packagist.org/packages/arjanwestdorp/imaginary-client)
[![License](https://poser.pugx.org/arjanwestdorp/imaginary-client/license?format=flat-square)](https://packagist.org/packages/arjanwestdorp/imaginary-client)
[![Build Status](https://img.shields.io/travis/arjanwestdorp/imaginary-client/master.svg?style=flat-square)](https://travis-ci.org/arjanwestdorp/imaginary-client)
[![Quality Score](https://img.shields.io/scrutinizer/g/arjanwestdorp/imaginary-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/arjanwestdorp/imaginary-client)
[![Coverage](https://img.shields.io/scrutinizer/coverage/g/arjanwestdorp/imaginary-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/arjanwestdorp/imaginary-client)

Client for the imaginary image service. Imaginary is a service build to resize images on the fly. Unfortunately that service is not open sourced yet.

## Installation
The recommended way to install the client is through composer:
~~~
composer require arjanwestdorp/imaginary-client
~~~

## Usage
~~~PHP
$client = new Client([
    'client' => 'CLIENT',
    'url' => 'https://imaginaryurl.com',
]);
 
$client->fetch('http://www.test.com/test.jpg')->url();
// https://imaginaryurl.com/CLIENT/image/fetch/http://www.test.com/test.jpg
 
$client->fetch('http://www.test.com/test.jpg')
    ->width(100)
    ->height(100)
    ->url();

// https://imaginaryurl.com/CLIENT/image/fetch/w_100,h_100/http://www.test.com/test.jpg
~~~

### Predefined definitions
You can add predefined sets of manipulations. The idea is that you can reuse the same manipulation multiple times in your whole application by defining it only once.
~~~
$client->define('landscape', function($imaginary){
    $imaginary->width(400)
        ->height(300)
        ->fit();
});
 
$client->fetch('http://www.test.com/test.jpg')
    ->landscape()
    ->url();
// https://imaginaryurl.com/CLIENT/image/fetch/w_400,h_300,c_fit/http://www.test.com/test.jpg
~~~

## Options

#### width($width)
Manipulate the width of the image in pixels.
 
#### height($height)
Manipulate the height of the image in pixels.

#### fit($gravity = null)
Fit the resizing in the given dimensions (width, height). By setting the gravity you can mark the position which the cutout will be taken from. By default it will cutout from the center.
Options are: 
- top-left
- top
- top-right
- left
- right
- center (default)
- bottom-left
- bottom
- bottom-right

#### circle($radius = 'max')
Make a circle image. Setting no radius, it will use the size of the image and make it a circle. By setting a radius the image will be resized to match that radius. If the image is bigger then the radius it will automatically use the `fit('center')` manipulation before making a circle.
The circle manipulation will be called after the resizing manipulations like width and height, so you can resize the image to your needs before applying the circle.

## Security

If you discover any security issues, please email arjanwestdorp@gmail.com instead of creating an issue.

## Credits

- [Arjan Westdorp](https://github.com/arjanwestdorp)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
