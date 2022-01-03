## What it does
This package allows you to add trait (similar to soft deletes) for Laravel Eloquent models.

In our projects we usually use soft deletes for models. When customers delete an object in the application, e.g. in the administration panel, they are usually not interested in whether the record was actually deleted from the table or just marked as deleted by using soft deletes.
However, there is often a need to temporarily hide an object, e.g. an article, user or a dictionary entry.

Then we use deactivable trait.

## Installation

Install the package via Composer:

```sh
$ composer require evostudio/deactivation
```

The package will automatically register its service provider.


## Updating your Eloquent Models

Your models should use the Deactivable trait.

```php
use EvoStudio\Deactivation\Deactivable;

class Client extends Model
{
    use Deactivable;
}
```