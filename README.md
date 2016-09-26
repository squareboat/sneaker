# Laravel Exception Notifications

An easy way to send emails with stack trace whenever an exception occurs on the server for Laravel Applications.

![sneaker example image](sneaker.png?raw=true "Sneaker")

## Install

### Install via composer

```
$ composer require squareboat/sneaker
```

### Configure Laravel

Once installation operation is complete, simply add the service provider to your project's `config/app.php` file:

#### Service Provider
```
SquareBoat\Sneaker\SneakerServiceProvider::class,
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
$ php artisan vendor:publish --provider="SquareBoat\Sneaker\SneakerServiceProvider"
```

The config file will be published in  `config/sneaker.php`

Following are the configuration attributes used for the sneaker.

#### silent

The package comes with `'silent' => true,` configuration by default, since you probably don't want error emailing enabled on your development environment. Especially if you've set `'debug' => true,`.

```php
'silent' => env('SNEAKER_SILENT', true),
```

For sending emails when an exception occurs set `SNEAKER_SILENT=false` in your `.env` file.


#### capture

It contains the list of the exception types that should be captured. You can add your exceptions here for which you want to send error emails.

By default, the package has included `Symfony\Component\Debug\Exception\FatalErrorException::class`.

```php
'capture' => [
    Symfony\Component\Debug\Exception\FatalErrorException::class,
],
```

You can also use `'*'` in the `$capture` array which will in turn captures every exception. To use this feature you should add the following code in `App/Exceptions/Handler.php`:

```php
public function report(Exception $e)
{
    if($this->shouldReport()) {
        app('sneaker')->captureException($e);
    }

    parent::report($e);
}
```

#### to

This is the list of recipients of error emails.

```php
'to' => [
        // 'hello@example.com',
    ],
```

#### ignored_bots

This is the list of bots for which we should NOT send error emails.

```php
'ignored_bots' => [
    'googlebot',        // Googlebot
    'bingbot',          // Microsoft Bingbot
    'slurp',            // Yahoo! Slurp
    'ia_archiver',      // Alexa
],
```

## Customize

If you need to customize the subject and body of email, run following command:

```bash
$ php artisan vendor:publish --provider="SquareBoat\Sneaker\SneakerServiceProvider"
```

> Note - Don't run this command again if you have run it already.

Now the email's subject and body view are located in the `resources/views/vendor/sneaker` directory.

We have passed the thrown exception object `$exception` in the view which you can use to customize the view to fit your needs.

# License

The MIT License. Please see [License File](LICENSE.md) for more information. Copyright © 2016 [SquareBoat](https://squareboat.com)
