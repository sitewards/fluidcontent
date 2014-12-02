Fluidcontent: Fluid Content Elements
====================================

> **Fluid Content** enables the use of special content elements, each based on a Fluid template - much like TemplaVoila's flexible
> content elements. The feature was born in the extension FED and ported into this extension, making a very light extension with
> a simple, highly specific purpose. It uses Flux to enable highly dynamic configuration of variables used when rendering the
> template.

[![Build Status](https://img.shields.io/jenkins/s/https/jenkins.fluidtypo3.org/fluidcontent.svg?style=flat-square)](https://jenkins.fluidtypo3.org/job/fluidcontent) [![Coverage Status](https://img.shields.io/coveralls/FluidTYPO3/fluidcontent/development.svg?style=flat-square)](https://coveralls.io/r/FluidTYPO3/fluidcontent) [![Build status](http://img.shields.io/badge/documentation-online-blue.svg?style=flat-square)](https://fluidtypo3.org/templating-manual/introduction.html)

## What does it do?

EXT:fluidcontent lets you write custom content elements based on Fluid templates. Each content element and its possible settings
are contained in a single Fluid template file. Whole sets of files can be registered and placed in its own tab in the new content
element wizard, letting you group your content elements. The template files are placed in a very basic extension.

The _Nested Content Elements_ support that Flux enables is utilized to make content elements which can contain other content
elements - and which can be edited inline in the pgage backend module (with native drag and drop support in 6.0 and drag and drop
support in 4.x branches through the Grid Elements extension - key `gridelements`).

## Why use it?

**Fluid Content** is a fast, dynamic and extremely flexible way to create content elements. Not only can you use Fluid, you can
also create dynamic configuration options for each content element using Flux - in the exact same way as done in the Fluid Pages
extension; see https://github.com/FluidTYPO3/fluidpages.

## How does it work?

Fluid Content Elements are registered through TypoScript. The template files are then processed to read various information about
each template, which is then made available for use just as any other content element type is used.

When editing the content element, Flux is used to generate the form section which lets content editors configure variables which
become available in the template. This allows completely dynamic variables (as opposed to adding extra fields on the tt_content
table and configuring TCA for each added column).

Content templates work best if they are shipped (and created) in an extension, the key of which is used by identify the content
templates in relation to the Fluid Content extension. This makes the templates excellently portable and allow you to quickly add
custom ViewHelpers used by your specific page templates. Such an extension need only contain an `ext_emconf.php` file and
optionally a static TypoScript configuration and an `ext_localconf.php` to register that TypoScript static configuration. Using
a static file makes it easy to include the content elements.

View the [online templating manual](https://fluidtypo3.org/documentation/templating-manual/introduction.html) for more information.
