# Omnipay: Square

**Square driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/Transportersio/omnipay-square.png?branch=master)](https://travis-ci.org/Transportersio/omnipay-square)
[![Latest Stable Version](https://poser.pugx.org/transportersio/omnipay-square/version.png)](https://packagist.org/packages/transportersio/omnipay-square)
[![Total Downloads](https://poser.pugx.org/transportersio/omnipay-square/d/total.png)](https://packagist.org/packages/transportersio/omnipay-square)
[![License](https://poser.pugx.org/transportersio/omnipay-square/license)](https://packagist.org/packages/transportersio/omnipay-square)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Square support for Omnipay, however `it increases the minimum version of PHP to 7.1.`

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "transportersio/omnipay-square": "~1.0.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Usage Example

Set two environment variables for the access token and location id from square.
`SQUARE_ACCESS_TOKEN` and `SQUARE_LOCATION_ID`.

The code to redirect a user to square checkout should look something like this:
```php
//define order details
$total_price = 10.99;

$selected_items = [
    'name'=>'Shoe',
    'price'=>'10.99',
    'quantity'=>1
];

//define a url that the user will be sent back to (with GET variables for transactionId, checkoutId, etc - see square API docs for details) 
$return_url = 'http://example.com/order-complete';

//tell square about the order and then send the user to checkout
try
{
    /** @var Omnipay\Square\Gateway $gateway */
    $gateway = \Omnipay\Omnipay::create('Square');
    $gateway->setAccessToken(getenv('SQUARE_ACCESS_TOKEN'));
    $gateway->setLocationId(getenv('SQUARE_LOCATION_ID'));
    $gateway->setCurrency('USD');
    $gateway->includeShippingAddress($include_shipping_address);

    /** @var \Omnipay\Common\Message\AbstractRequest $request */
    $request = $gateway->purchase([
        'amount' => $total_price
    ]);

    $request->setItems($selected_items);
    if( $return_url ) {
        $request->setReturnUrl($return_url);
    }

    /** @var \Omnipay\Square\Message\WebPaymentResponse $response */
    $response = $request->send();

    if( !$response->isSuccessful() )
    {
        //handle error with $response->getMessage();
    }

    header('location: '.$response->getData()['checkout_url']);
}
catch (Throwable $e)
{
    //handle exception
}
```

The following gateways are provided by this package:

* Square

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/Transportersio/omnipay-square/issues),
or better yet, fork the library and submit a pull request.
