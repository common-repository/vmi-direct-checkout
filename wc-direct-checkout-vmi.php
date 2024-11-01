<?php
/**
 * @package Buy Now Woocommerce
 */
/*
Plugin Name: Buy Now Woocommerce
Plugin URI: https://vibrantmediainc.com/
Description: This plugin is used for adding a <strong> Buy Now</strong> button in Product page & Proceed to checkout directly.
Version: 3.0.3
Author: Vibrant Media Inc.
Author URI: https://vibrantmediainc.com/
License: GPLv2 or later
Text Domain: Buy Now Woocommerce
*/


// FILE ADD FOR ADMIN
add_action('admin_head', 'vmi_admin_CSS_JS_File');


if (!function_exists('vmi_admin_CSS_JS_File')){
function vmi_admin_CSS_JS_File() {
define( 'VMI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
include( VMI_PLUGIN_PATH . 'assets/css/style.css');
include( VMI_PLUGIN_PATH . 'assets/js/script.js');
}
}

if (!function_exists('vmi_create_table')){
function vmi_create_table(){
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$default_value_buttontext = 'Buy Now';
$default_value_bgcolor = '#cccccc';
$default_value_textcolor = '#000000';
$default_value_cartHide = 0;
$dirctcheckoutvmi_buybtn = 0;
if (FALSE === get_option('dirctcheckoutvmi_buttontext') && FALSE === update_option('dirctcheckoutvmi_buttontext',FALSE)){
 add_option('dirctcheckoutvmi_buttontext',$default_value_buttontext);
}

if (FALSE === get_option('dirctcheckoutvmi_bgcolor') && FALSE === update_option('dirctcheckoutvmi_bgcolor',FALSE)){
 add_option('dirctcheckoutvmi_bgcolor',$default_value_bgcolor);
}

if (FALSE === get_option('dirctcheckoutvmi_textcolor') && FALSE === update_option('dirctcheckoutvmi_textcolor',FALSE)){
 add_option('dirctcheckoutvmi_textcolor',$default_value_textcolor);
}

if (FALSE === get_option('dirctcheckoutvmi_cartHide') && FALSE === update_option('dirctcheckoutvmi_cartHide',FALSE)){
 add_option('dirctcheckoutvmi_cartHide',$default_value_cartHide);
}

if (FALSE === get_option('dirctcheckoutvmi_buybtn') && FALSE === update_option('dirctcheckoutvmi_buybtn',FALSE)){
 add_option('dirctcheckoutvmi_buybtn',$dirctcheckoutvmi_buybtn);
}
}
}



register_activation_hook(__FILE__, 'vmi_create_table');

register_activation_hook(__FILE__, 'vmi_plugin_activate');
add_action('admin_init', 'vmi_plugin_redirect');

if (!function_exists('vmi_plugin_activate')){
function vmi_plugin_activate() {
    add_option('my_plugin_do_activation_redirect', true);
}
}


if (!function_exists('vmi_plugin_redirect')){
function vmi_plugin_redirect() {
    if (get_option('my_plugin_do_activation_redirect', false)) {
        delete_option('my_plugin_do_activation_redirect');
        wp_redirect("admin.php?page=buy-now-woo");
    }
}
}


if (!function_exists('vmi_woocommerce_template_single_buynow')){
function vmi_woocommerce_template_single_buynow()
{
  global $product;
  $buttontext = get_option('dirctcheckoutvmi_buttontext');
  $bgcolor = get_option('dirctcheckoutvmi_bgcolor');
  $textcolor = get_option('dirctcheckoutvmi_textcolor');
  $addtocart_url = wc_get_checkout_url().'?add-to-cart='.$product->get_id();
  $button_class  = 'vmi_single_add_to_cart_button vmi_button vmi_alt vmi_custom-checkout-btn';
  $html = '<a href="'.$addtocart_url.'" id="vmiDirectCheckoutBtn" class="'.$button_class.'">'.$buttontext.'</a>    <style>
    .vmi_custom-checkout-btn 
  {
      padding: 10px 40px;
      color:'.$textcolor.';
      background-color: '.$bgcolor.';
      border-radius:50px;
      font-weight:bold;
      text-transform:uppercase;
      letter-spacing:1px;
      display: inline-block;
  }
  .vmi_custom-checkout-btn:hover 
  {
      color:'.$bgcolor.';
      background-color:'.$textcolor.';
  }


   <style>';
  echo $html;
}
}

add_action( 'init', 'dirctcheckoutvmi_setup_pro' );
if (!function_exists('dirctcheckoutvmi_setup_pro')){
function dirctcheckoutvmi_setup_pro() {
  $cartHide = get_option('dirctcheckoutvmi_cartHide');
  $buybtn = get_option('dirctcheckoutvmi_buybtn');
  if($cartHide == 1){ remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30); }
  if($buybtn == 0){ add_action('woocommerce_single_product_summary','vmi_woocommerce_template_single_buynow',30); }
}
}


if (!function_exists('vmi_admin_menu')){
function vmi_admin_menu() {
add_menu_page(

__( 'Buy Now Woo', 'buy-now-woo' ),

__( 'Buy Now Woo', 'buy-now-woo' ),

'manage_options',

'buy-now-woo',

'vmi_admin_page_contents',

'dashicons-cart',

5
);

}
}

add_action( 'admin_menu', 'vmi_admin_menu' );

if (!function_exists('vmi_admin_page_contents')){
function vmi_admin_page_contents() {
if(isset($_POST['action']) && wp_verify_nonce($_POST['nonce'], 'dirctcheckoutvmicall') ){
      $cusbuttontext = sanitize_text_field($_POST["cusbuttontext"]);
      $bgcolors = sanitize_hex_color($_POST["bgcolors"]);
      $textcolors = sanitize_hex_color($_POST["textcolors"]);
      update_option('dirctcheckoutvmi_buttontext', $cusbuttontext);
      update_option('dirctcheckoutvmi_bgcolor', $bgcolors);
      update_option('dirctcheckoutvmi_textcolor', $textcolors);

      
      if(isset($_POST['cartHide']) && $_POST['cartHide'] == '0'){      
        update_option('dirctcheckoutvmi_cartHide',1);
      }
      else{
        update_option('dirctcheckoutvmi_cartHide',0);        
      }
      if(isset($_POST['buybtn']) && $_POST['buybtn'] == '0'){      
        update_option('dirctcheckoutvmi_buybtn',1);
      }
      else{
        update_option('dirctcheckoutvmi_buybtn',0);        
      }      
      
}
$buttontext = get_option('dirctcheckoutvmi_buttontext');
$bgcolor = get_option('dirctcheckoutvmi_bgcolor');
$textcolor = get_option('dirctcheckoutvmi_textcolor');
$cartHide = get_option('dirctcheckoutvmi_cartHide');
$buybtn  = get_option('dirctcheckoutvmi_buybtn');

if( $cartHide == 1) {
  $cartCheck = "checked";
}
if( $buybtn == 1) {
  $buyCheck = "checked";
}

$admin_html = '<h1>Buy Now Woocommerce</h1><div class="notice notice-warning is-dismissible" style="margin:0px;max-width: 430px;">
<p>Give us a 5 Stars Review <a href="https://wordpress.org/support/plugin/vmi-direct-checkout/reviews/#new-post" target="_blank">Give Review</a></p>
</div>
<br>
<div class="inner-panel-dash-vmi">
   <form method="post"> 
   <input type="hidden" value="dirctcheckoutvmicall" name="action"/>
   <input type="hidden" value="'.wp_create_nonce( 'dirctcheckoutvmicall' ).'" name="nonce"/>
  <div class="inner-container">
  <label for="cartHide" class="nametext-plugin">Hide Add to Cart Button</label>
  <label class="switch">
  <input type="checkbox" name="cartHide" value="0" '.$cartCheck.'>
  <span class="slider round"></span>
  </label>
  </div>

  <div class="inner-container">
  <label for="buybtn" class="nametext-plugin">Hide Buy Now Button</label>
  <label class="switch">
  <input type="checkbox" name="buybtn" value="0" '.$buyCheck.'>
  <span class="slider round"></span>
  </label>
 </div>

  <div class="inner-container">  
   <label for="bgcolor" class="nametext-plugin">Background Color</label>
   <div class="input-color-container">
   <input type="color" id="bgcolors" class="input-plugin input-color" name="bgcolors" value="'.$bgcolor.'">
   </div>
    </div>

  <div class="inner-container">
   <label for="txtcolor" class="nametext-plugin">Text Color</label>
   <div class="input-color-container">   
   <input type="color" class="input-plugin input-color" id="txtcolors" name="textcolors" value="'.$textcolor.'">
   </div>
   </div>

  <div class="inner-container input-btn-text-container">
   <label class="nametext-plugin">Enter Your Text Here </label>
   <input type="text" class="input-plugin" name="cusbuttontext" required="required" value="'.$buttontext.'" placeholder="Buy Now">
   </div>

   <input type="submit" class="submit-plugin-btn" value="Update" name="submit"/>
   
   <p class="vmi-checkout-suggestion">Buy now button styling use this ID <input disabled="" value="#vmiDirectCheckoutBtn"> </p>
</form>
</div>
  <br>
   <img src="'.plugin_dir_url( __FILE__ ).'assets/icon-256x256.png" width="140px">
   <br>
   <a href="https://wordpress.org/support/plugin/vmi-direct-checkout/reviews/#new-post" target="_blank">Give us a 5 Stars Review</a>';
//esc_html( $admin_html );
echo $admin_html;
}
}