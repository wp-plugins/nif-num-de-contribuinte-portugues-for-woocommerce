<?php
/**
 * Plugin Name: NIF (Num. de Contribuinte Português) for WooCommerce
 * Plugin URI: http://www.webdados.pt/produtos-e-servicos/internet/desenvolvimento-wordpress/nif-de-contribuinte-portugues-woocommerce-wordpress/
 * Description: This plugin adds the Portuguese VAT identification number (NIF/NIPC) as a new field to WooCommerce checkout and order details, if the billing address is from Portugal.
 * Version: 1.3
 * Author: Webdados
 * Author URI: http://www.webdados.pt
 * Text Domain: woocommerce_nif
 * Domain Path: /lang
**/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Check if WooCommerce is active
 **/
// Get active network plugins - "Stolen" from Novalnet Payment Gateway
function nif_active_nw_plugins() {
	if (!is_multisite())
		return false;
	$nif_activePlugins = (get_site_option('active_sitewide_plugins')) ? array_keys(get_site_option('active_sitewide_plugins')) : array();
	return $nif_activePlugins;
}
if (in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins')) || in_array('woocommerce/woocommerce.php', (array) nif_active_nw_plugins())) {


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
			$current_user=wp_get_current_user();
			$fields['billing']['billing_nif'] = array(
				'type'			=>	'text',
				'label'			=> __('NIF / NIPC', 'woocommerce_nif'),
				'placeholder'	=> _x('Portuguese VAT identification number', 'placeholder', 'woocommerce_nif'),
				'class'			=> array('form-row-first'),
				'required'		=> false,
				'clear'			=> true,
				'default'		=> ($current_user->billing_nif ? trim($current_user->billing_nif) : ''),
			);
		}
		return $fields;
	}


	//Add NIF to My Account / Billing Address form
	add_filter('woocommerce_address_to_edit', 'woocommerce_nif_my_account');
	function woocommerce_nif_my_account($fields) {
		global $wp_query;
		if (isset($wp_query->query_vars['edit-address']) && $wp_query->query_vars['edit-address']!='billing') {
			return $fields;
		} else {
			$current_user=wp_get_current_user();
			if ($current_user->billing_country=='PT') {
				$fields['billing_nif']=array(
					'type'			=>	'text',
					'label'			=> __('NIF / NIPC', 'woocommerce_nif'),
					'placeholder'	=> _x('Portuguese VAT identification number', 'placeholder', 'woocommerce_nif'),
					'class'			=> array('form-row-first'),
					'required'		=> false,
					'clear'			=> true,
					'default'		=> ($current_user->billing_nif ? trim($current_user->billing_nif) : ''),
				);
			}
			return $fields;
		}
	}
	//Save NIF to customer Billing Address
	add_action('woocommerce_customer_save_address', 'woocommerce_nif_my_account_save', 10, 2);
	function woocommerce_nif_my_account_save($user_id, $load_address) {
		if ($load_address=='billing') {
			if (isset($_POST['billing_nif'])) {
				update_user_meta( $user_id, 'billing_nif', trim($_POST['billing_nif']) );
			}
		}
	}


	//Add field to order admin panel
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'woocommerce_nif_admin', 10, 1);
	function woocommerce_nif_admin($order){
		if (@is_array($order->order_custom_fields['_billing_country'])) {
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