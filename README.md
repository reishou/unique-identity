## unique-identity
Generator unique identity 64 bits and combine with laravel eloquent.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-gha]][link-gha]
[![Total Downloads][ico-downloads]][link-downloads]

- [Overview](#overview)
- [Installation](#installation)
- [Usage](#usage)
    - [Automatic when eloquent boot creating](#automatic-when-eloquent-boot-creating)
    - [Manual generate list uid](#manual-generate-list-uid)
- [License](#license)
- [Changelog](#changelog)

### Overview

This project inspires from the article [Sharding & IDs at Instagram][original-article].
With it, you can create uid for your table:
- 64-bits length.
- sortable by time.

### Installation

Require this package with composer using the following command:

```shell
composer require reishou/unique-identity
```

Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

Publish the config file with:

```shell
php artisan vendor:publish --provider="Reishou\UniqueIdentity\UidServiceProvider"
```
You can change `entity_table` name in `config/uid.php` (default `entity_sequences`).
Then run command generate migration:

```shell
php artisan uid:table
```

After the migration has been generated you can create the `entity_table` by running:

```
php artisan migrate
```

### Usage

#### Automatic when eloquent boot creating
Your Eloquent models should use the `Reishou\UniqueIdentity\HasUid` trait.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Reishou\UniqueIdentity\HasUid;

class YourModel extends Model
{
    use HasUid;
}
```

The `primaryKey` will be filled auto by uid generator when model created or saved for the first time.

```shell
// instantiate a new model
$model = new YourModel();
// set attributes on the model
$model->field = $value;
// save model
$model->save();
// or use the create method to "save" a new model
YourModel::create($attributes)
```

#### Manual generate list uid
You can generate multi uid for multi record before inserting to database.

```php
// $listAttributes contain 10 elements, every element is an array attributes will insert to database.
$listAttributes = [...]; 
// array $ids contains 10 uid
$ids = YourModel::uid(count($listAttributes));
// set ids to attributes
$listAttributes = collect($listAttributes)->map(function ($attributes, $index) use ($ids) {
    $attributes['id']         = $ids[$index];
    $attributes['created_at'] = now();
    $attributes['updated_at'] = now();
    
    return $attributes;
})
    ->toArray();
// insert to database
YourModel::insert($listAttributes);
```

### License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

[original-article]: https://instagram-engineering.com/sharding-ids-at-instagram-1cf5a71e5a5c
[ico-version]: https://img.shields.io/packagist/v/reishou/unique-identity.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-gha]: https://github.com/reishou/unique-identity/actions/workflows/php.yml/badge.svg
[ico-downloads]: https://img.shields.io/packagist/dt/reishou/unique-identity.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/reishou/unique-identity
[link-gha]: https://github.com/reishou/unique-identity/actions
[link-downloads]: https://packagist.org/packages/reishou/unique-identity