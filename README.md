# credo/credo-php

###### This package is for communicating with CREDO RESTful API. [Credo](https://credocentral.com/)
Having other resource point available on CREDO API, Resources like;
- Transaction
- 3D Secure Payment via Card
- Verify Card Number
- Direct Card Charges
- and many more

Just to name a few, it is only the Transaction Resource that is made available currently in this package. Development is ongoing while releases are Stable.

<br>

## Requirements
- Curl

## Install

### Via Composer

``` bash
$ composer require credoteam/credo
```
If you use a Framework, check your documentation for how vendor packages are autoloaded else Add this to the top of your source file;

``` php
require_once __DIR__ . "/vendor/autoload.php";
```

## Making Transactions/Recieving Payment

### Starting Up Credo Transaction

``` php

use Credoteam\Credo\Transaction;
use Credoteam\Credo\Helpers\Debugger;
use Credoteam\Credo\Helpers\Requesters;

$publicKey =  "pk_demo-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx.xxxxxxxx-d";
$secretKey = "sk_demo-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx.xxxxxxxxx-d";

// creating the transaction object
$Transaction = new Transaction( $publicKey );
```

### Initializing Transaction

Set data/payload/requestBody to post with initialize request. Minimum required data are email and amount.

``` php
// Set data to post using array
$data = [
  "amount"=> 3000,
  "currency"=> "NGN",
  "redirectUrl"=> "http://localhost:8080/test-credo/welcome/",
  "transRef"=> "748rbrio4823ruoqedb9h4378e", //you can send your transaction ref or allow Credo generate for you
  "paymentOptions"=> "CARD",
  "customerEmail"=> "adiegodswill17@gmail.com",
  "customerName"=> "Adie Godswill",
  "customerPhoneNo"=> "09021960905"
];

$response = $Transaction->initialize($data);
```
If you want to get the 200OK raw Object as it is sent by Credo, Set the 2nd argument of the `initialize()` to `true`, example below
``` php
// Set data to post using this method
$response =
        $Transaction
            ->setEmail( 'adiegodswill17@gmail.com' )
            ->setAmount( 23000 )
            ->initialize([], true);
```
Now do a redirect to payment page (using redirectUrl)
<br>
NOTE: Recommended to Debug `$response` or check if redirectUrl is set, and save your Transaction reference code. useful to verify Transaction status

``` php
// recommend to save Transaction reference in database and do a redirect
$transRef = $response->transRef;
// redirect
Http::redirect($response->redirectUrl);
```
Using a Framework? It is recommended you use the reverse routing/redirection functions provided by your Framework


### Verifying Transaction

It is also imperative that you create Transaction Object once more.
<br>
This method would return the Transaction Object but `false` if saved `$transRef` is not passed in as argument and also cant be guessed. Using `verify()` would require you do a manual check on the response Object
``` php
// creating the transaction object
$Transaction = new Transaction( $secretKey );

// Set data to post using this method
$response = $Transaction->verify($transRef);

// Debuging the $response
Debugger::print_r( $response);
```
OR
``` php
// This method does the check for you and return `(bool) true|false`
$response = $Transaction->isSuccessful();
```
The two methods above try to guess your Transaction `$transRef` but it is highly recommended you pass the Transaction `$transRef` as an argument on the method as follows
``` php
// This method does the check for you and return `(bool) true|false`
$response = $Transaction->isSuccessful($transRef);

```
### Direct Card Charge

Set data/payload/requestBody to post with direct charge request.
``` php
// Set data to post using array
$data = [
  "orderAmount" => 400,
  "orderCurrency" => "NGN",
  "cardNumber" => 4242424242424242,
  "expiryMonth" => 1,
  "expiryYear" => 22,
  "securityCode" => 439,
  "transRef" => "748rbri4823ruoqedb9h435",
	"customerEmail" => "adiegodswill17@gmail.com",
	"customerName" => "Adie Godswill",
	"customerPhoneNo" => "09021960905"
];

// creating the transaction object
$Transaction = new Transaction( $secretKey );

// Set data to post using this method
$response = $Transaction->direct_charge($data);

```

### Verify Card Number

Set data/payload/requestBody to post with verifying a card number request.
``` php
// Set data to post using array
$data = [
 	"cardNumber" => 4242424242424242,
 	"orderCurrency" => "NGN",
  "paymentSlug" => "pIEiYn8xxxxxxxxxxxxx"
];

// creating the transaction object
$Transaction = new Transaction( $secretKey );

// Set data to post using this method
$response = $Transaction->verify_card_number($data);

```

### 3D Secure Payment via Card

Set data/payload/requestBody to post with performing a 3D Secure payment request.
``` php
// Set data to post using array
$data = [
  "amount" => 4500,
  "currency" => "NGN",
  "redirectUrl" => "http://localhost/go",
  "transRef" => "748389842939e3",
  "paymentOptions" => "CARD",
  "customerEmail" => "adiegodswill17@gmail.com",
  "customerName" => "Adie Godswill",
  "customerPhoneNo" => "09021960905"
];

// creating the transaction object
$Transaction = new Transaction( $secretKey );

// Set data to post using this method
$response = $Transaction->payment_3ds($data);
```

## Contributions
If you seem to understand the architecture, you are welcome to fork and pull else you can wait a bit more till when we provide convention documentation.

## Licence
GNU GPLV3
