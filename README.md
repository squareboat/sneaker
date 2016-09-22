# Laravel Exception Notifications

An easy way to send emails whenever an exception occurs on server for Laravel Applications.

## Install

### Install via composer

```
$ composer require squareboat/sneaker dev-master
```

or

Add dependency to your `composer.json` file and run composer update.

```
require: {
    "squareboat/sneaker": "dev-master"
}
```

### Configure Laravel

Once installation operation is complete, simply add the service provider to your project's `config/app.php` file:

#### Service Provider
```
Squareboat\Sneaker\SneakerServiceProvider::class,
```


### Add Sneaker's Exception Capturing

Add exception capturing to `App/Exceptions/Handler.php`:

```php
public function report(Exception $e)
{
    app('sneaker')->captureException($e);

    parent::report($e);
}
```

### Configuration File

Create the Sneaker configuration file  with this command:

```bash
$ php artisan vendor:publish --provider="Squareboat\Sneaker\SneakerServiceProvider"
```

The config file will be published in  `config/sneaker.php`

Following are the configuration attributes used for the sneaker.

#### silent

The package comes with `'silent' => true,` configuration by default, since you probably don't want error emailing enabled on your development environment. Especially if you've set `'debug' => true,`.

For sending emails when an exception occurs set `SNEAKER_SILENT=false` in your `.env` file.


#### capture

It contains the list of the exception types that should be captured. You can add your exceptions here for which you want to send error emails.

By default package has included `Symfony\Component\Debug\Exception\FatalErrorException::class`.

#### to

This is the list of recipients of error emails.

#### ignored_bots

This is the list of bots for which we should NOT send error emails.

# License

The MIT License. Please see [License File](LICENSE.md) for more information. Copyright © SquareBoat 2016
