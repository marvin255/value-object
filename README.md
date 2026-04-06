# Value objects

[![Latest Stable Version](https://poser.pugx.org/marvin255/value-object/v)](https://packagist.org/packages/marvin255/value-object)
[![Total Downloads](https://poser.pugx.org/marvin255/value-object/downloads)](https://packagist.org/packages/marvin255/value-object)
[![License](https://poser.pugx.org/marvin255/value-object/license)](https://packagist.org/packages/marvin255/value-object)
[![Build Status](https://github.com/marvin255/value-object/workflows/marvin255_value_object/badge.svg)](https://github.com/marvin255/value-object/actions?query=workflow%3A%22marvin255_value_object%22)

A collection of type-safe value objects for PHP 8.3+ with built-in validation. Includes email, URI, integer, string, and file info objects designed for domain-driven development.

## Installation

Install the package via Composer:

```bash
composer require marvin255/value-object
```

## Usage

### String Value Objects

Create type-safe string values with optional constraints:

```php
use Marvin255\ValueObject\StringValueObject;
use Marvin255\ValueObject\StringNonEmptyValueObject;

// Basic string value object
$name = new StringValueObject('John Doe');
echo $name->value(); // "John Doe"

// String that cannot be empty
$title = new StringNonEmptyValueObject('Developer');
echo $title->value(); // "Developer"
```

### Integer Value Objects

Work with integers using type-safe value objects:

```php
use Marvin255\ValueObject\IntValueObject;
use Marvin255\ValueObject\IntPositiveValueObject;
use Marvin255\ValueObject\IntNonNegativeValueObject;
use Marvin255\ValueObject\IntNegativeValueObject;
use Marvin255\ValueObject\IntNonPositiveValueObject;

// Any integer
$count = new IntValueObject(-5);
echo $count->value(); // -5

// Positive integer only (> 0)
$age = new IntPositiveValueObject(25);
echo $age->value(); // 25

// Non-negative integer (>= 0)
$score = new IntNonNegativeValueObject(0);
echo $score->value(); // 0

// Negative integer only (< 0)
$debt = new IntNegativeValueObject(-100);
echo $debt->value(); // -100

// Non-positive integer (<= 0)
$offset = new IntNonPositiveValueObject(-5);
echo $offset->value(); // -5
```

### Float Value Objects

Work with floating-point numbers safely:

```php
use Marvin255\ValueObject\FloatValueObject;

// Any float value
$price = new FloatValueObject(19.99);
echo $price->value(); // 19.99
```

### BCMath Value Object

Arbitrary-precision decimal numbers using BcMath:

```php
use Marvin255\ValueObject\BcMathNumberValueObject;

$file = new BcMathNumberValueObject('123.123123');
echo $empty->value(); // "123.123123"
```

### Percentage Value Object

Work with percentage values (0-100) safely:

```php
use Marvin255\ValueObject\PercentageValueObject;

// Percentage between 0 and 100
$progress = new PercentageValueObject(75.5);
echo $progress->value(); // 75.5

$complete = new PercentageValueObject(100);
echo $complete->value(); // 100

$empty = new PercentageValueObject(0.0);
echo $empty->value(); // 0

// Decimal precision supported
$accuracy = new PercentageValueObject(99.99);
echo $accuracy->value(); // 99.99

// This will throw an exception
// new PercentageValueObject(101); // throws InvalidArgumentException
```

### Email Value Object

Validate and use email addresses:

```php
use Marvin255\ValueObject\EmailValueObject;

$email = new EmailValueObject('user@example.com');
echo $email->value(); // "user@example.com"
```

### URI Value Object

Create validated URI objects:

```php
use Marvin255\ValueObject\UriValueObject;

$uri = new UriValueObject('https://example.com/path?query=value');
echo $uri->value(); // "https://example.com/path?query=value"
```

### File Info Value Object

Work with file information safely:

```php
use Marvin255\ValueObject\FileInfoValueObject;

$file = new FileInfoValueObject('/path/to/file.txt');
echo $file->value()->getPathname(); // "/path/to/file.txt"
echo $file->getMimeType();          // "text/plain"
```
