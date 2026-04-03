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
