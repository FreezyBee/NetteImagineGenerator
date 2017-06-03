Quickstart
==========

[![Build Status](https://travis-ci.org/FreezyBee/NetteImagineGenerator.svg?branch=master)](https://travis-ci.org/FreezyBee/NetteImagineGenerator)
[![Coverage Status](https://coveralls.io/repos/github/FreezyBee/NetteImagineGenerator/badge.svg?branch=master)](https://coveralls.io/github/FreezyBee/NetteImagineGenerator?branch=master)

Requirements
------------

- PHP 7.1+
- Nette 2.4+
- FreezyBee/PrependRoute

Installation
------------

```sh
$ composer require freezy-bee/nette-imagine-generator
```

```yml
extensions:
    prependRoute: FreezyBee\PrependRoute\DI\PrependRouteExtension
    imagineGenerator: FreezyBee\NetteImagineGenerator\DI\ImagineGeneratorExtension

imagineGenerator:
    routes:
        - '/generated/<id>--<crop crop>.<extension>'
        - '/generated/<namespace>/<id>--<width [0-9]+>x<height [0-9]+>.<extension>'
        - '/generated/<namespace>/<id>--<width [0-9]+>.<extension>'
        - '/generated/<namespace>/<id>.<extension>'
    providers:
        - App\ImageProviders\CopyProviderMock

```

Usage
-----

Image MUST implements `FreezyBee\NetteImagineGenerator\RequestedImagineInterface`.

```html
    <!-- using n:macro -->
    <!-- image, [width], [height], [params...] -->
    <img n:src="$image">
    <img n:src="$image, 100">
    <img n:src="$image, 100, 200">
    <img n:src="$image, 100, 200, crop => crop">
    <img n:src="$image, param => ok">
    
    <!-- using classic macro -->
    <meta content="{linkSrc $image}">
```