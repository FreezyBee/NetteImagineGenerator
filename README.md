Quickstart
==========

[![Build Status](https://travis-ci.org/FreezyBee/NetteImagineGenerator.svg?branch=master)](https://travis-ci.org/FreezyBee/NetteImagineGenerator)
[![Coverage Status](https://coveralls.io/repos/github/FreezyBee/NetteImagineGenerator/badge.svg?branch=master)](https://coveralls.io/github/FreezyBee/NetteImagineGenerator?branch=master)

Installation
------------

The best way to install FreezyBee/NetteImagineGenerator is using  [Composer](http://getcomposer.org/):

```sh
$ composer require freezy-bee/nette-imagine-generator
```

With Nette `2.4` and newer, you can enable the extension using your neon config.

```yml
extensions:
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
