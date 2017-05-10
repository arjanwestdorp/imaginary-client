# Imaginary client
Client for the imaginary image service.

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
You can add predefined sets of manipulations. The idea is that you can reuse the same manipulation multiple times through your whole application by defining it only once.
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
Fit the resizing in the given dimensions (width, height). By setting the gravity you can mark the position which the image will be cutout from. By default it will cutout from the center.
Options are: 
- top-left
- top
- top-right
- left
- center (default)
- right
- bottom-left
- bottom
- bottom-right

#### circle($radius = 'max')
Make a circle image. Setting no radius uses the size of the image and make it a circle. By setting a radius the circle will be that size. If the image is bigger then the radius it will automatically use the `fit('center')` manipulation before making a circle.
The circle manipulation will be called after the resizing manipulations like width and height.
