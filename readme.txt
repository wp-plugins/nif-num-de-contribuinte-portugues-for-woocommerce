=== NIF (Num. de Contribuinte Português) for WooCommerce ===
Contributors: webdados, wonderm00n
Tags: woocommerce, ecommerce, e-commerce, nif, nipc, vat, tax, portugal
Author URI: http://www.webdados.pt
Plugin URI: http://www.webdados.pt/produtos-e-servicos/internet/desenvolvimento-wordpress/nif-de-contribuinte-portugues-woocommerce-wordpress/
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 1.3

This plugin adds the Portuguese NIF/NIPC as a new field to WooCommerce checkout and order details, if the billing address is from Portugal.

== Description ==

This plugin adds the Portuguese VAT identification number (NIF/NIPC) as a new field to WooCommerce checkout and order details, if the billing address is from Portugal.

= Features: =

* Adds the Portuguese VAT identification number (NIF/NIPC) to the checkout fields;
* Adds the Portuguese VAT identification number (NIF/NIPC) to the order admin fields;

== Installation ==

* Use the included automatic install feature on your WordPress admin panel and search for "NIF WooCommerce".

== Frequently Asked Questions ==

= How to make the NIF field required? =

Just add this to your theme's functions.php file:

`add_filter('woocommerce_checkout_fields', 'woocommece_nif_checkout_required', 11);
function woocommece_nif_checkout_required($fields) {
	$fields['billing']['billing_nif']['required']=true;
	return $fields;
}`

== Changelog ==

= 1.3 =
* Adds the field to the My Acccount / Edit Billing Address form

= 1.2.2 =
* The value is now auto filled with the last one used

= 1.2.1 =
* Small fix to avoid php notices

= 1.2 =
* WordPress Multisite support

= 1.1.1 =
* Forgot to update version number on the php file.

= 1.1 =
* Bug fix after WooCommerce 2.1 changes.

= 1.0 =
* Initial release.