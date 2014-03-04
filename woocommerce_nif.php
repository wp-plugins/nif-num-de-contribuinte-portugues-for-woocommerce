<?php
/**
 * Plugin Name: NIF (Num. de Contribuinte Português) for WooCommerce
 * Plugin URI: http://www.webdados.pt/produtos-e-servicos/internet/desenvolvimento-wordpress/nif-de-contribuinte-portugues-woocommerce-wordpress/
 * Description: This plugin adds the Portuguese VAT identification number (NIF/NIPC) as a new field to WooCommerce checkout and order details, if the billing address is from Portugal.
 * Version: 1.0
 * Author: Webdados
 * Author URI: http://www.webdados.pt
 * Text Domain: woocommerce_nif
 * Domain Path: /lang
**/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	//Languages
	add_action('plugins_loaded', 'woocommerce_nif_init');
	function woocommerce_nif_init() {
		load_plugin_textdomain('woocommerce_nif', false, dirname(plugin_basename(__FILE__)) . '/lang/');
	}
	
	//Add field to checkout
	add_filter('woocommerce_checkout_fields' , 'woocommerce_nif_checkout');
	function woocommerce_nif_checkout( $fields ) {
		global $woocommerce;
		if(trim($woocommerce->customer->get_country())=='PT') {
			$fields['billing']['billing_nif'] = array(
				'type'			=>	'text',
				'label'			=> __('NIF / NIPC', 'woocommerce_nif'),
				'placeholder'	=> _x('Portuguese VAT identification number', 'placeholder', 'woocommerce_nif'),
				'class'			=> array('form-row-first'),
				'required'		=> false,
				'clear'			=> true
			);
		}
		return $fields;
	}

	//Add field to order admin panel
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'woocommerce_nif_admin', 10, 1);
	function woocommerce_nif_admin($order){
		if (is_array($order->order_custom_fields['_billing_country'])) {
			//Old WooCommerce versions
			if(@in_array('PT', $order->order_custom_fields['_billing_country']) ) {
				echo "<p><strong>".__('NIF / NIPC', 'woocommerce_nif').":</strong> " . $order->order_custom_fields['_billing_nif'][0] . "</p>";
	  		}
		} else {
			//New WooCommerce versions
			if ($order->billing_country=='PT') {
				$order_custom_fields=get_post_custom($order->ID);
				echo "<p><strong>".__('NIF / NIPC', 'woocommerce_nif').":</strong> " . $order_custom_fields['_billing_nif'][0] . "</p>";
			}
		}
	}

	/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */
	
}