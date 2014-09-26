PAYMILL-OpenCart Extension for credit card and direct debit payments
====================

PAYMILL extension is compatible with: 1.5.4, 1.5.4.1, 1.5.5.1, 1.5.6 (tested for 1.5.4.1 and 1.5.5.1). This extension installs two payment methods: Credit card and direct debit.

## Your Advantages
* PCI DSS compatibility
* Payment means: Credit Card (Visa, Visa Electron, Mastercard, Maestro, Diners, Discover, JCB, AMEX, China Union Pay), Direct Debit (ELV)
* Optional fast checkout configuration allowing your customers not to enter their payment detail over and over during checkout
* Improved payment form with visual feedback for your customers
* Supported Languages: German, English, Portuguese, Italian, French, Spanish
* Backend Log with custom View accessible from your shop backend

## Installation from this git repository

Download the complete module by using the link below:

[Latest Version](https://github.com/Paymill/Paymill-OpenCart/archive/master.zip)

######Please note that Github will add an additional folder.
To install the extension merge the content of the folder `paymill-opencart-master` with your Opencart installation.

## Configuration

Go to Extentions > Payments and `install` your favorite payment method.
Afterwards you can click `edit` to enter your configuration.

## Notes about the payment process

The payment is processed when an order is placed in the shop frontend.
An invoice is being generated automatically.

There are several options altering this process:

Fast Checkout: Fast checkout can be enabled by selecting the option in the PAYMILL Settings. If any customer completes a purchase while the option is active this customer will not be asked for data again. Instead a reference to the customer data will be saved allowing comfort during checkout.

## In case of errors

In case of any errors turn on the debug mode and logging in the PAYMILL Settings. Open the javascript console in your browser and check what's being logged during the checkout process. To access the logged information not printed in the console please click the `Logging`-button within your configuration.

## Adding capture / refund

Open the file `admin/view/template/sale/order_info.tpl`.
Search for the following code
```php
<tr>
    <td><?php echo $text_country; ?></td>
    <td><?php echo $payment_country; ?></td>
</tr>
<tr>
    <td><?php echo $text_payment_method; ?></td>
    <td><?php echo $payment_method; ?></td>
</tr>
```

Add the folowing code right atfer the previous code
```php
<?php
    require_once(DIR_SYSTEM."../paymill/admin/template/paymentActions.tpl");
?>
```

Open the file `admin/controller/sae/order.php`
Search for the following code
```php
public function info() {
    $this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
```
and add the following code below it
```php
    $this->data['paymillshow'] = preg_match('/^paymill.*$/', $order_info['payment_code']);
    $this->data['paymillURL'] = $this->url->link('custom/paymillOrder', '&token=' . $this->session->data['token'] .'&orderId='.$order_id);
```
