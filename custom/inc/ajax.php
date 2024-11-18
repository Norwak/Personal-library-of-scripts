<?php
/**
 * Return an amount of items in cart
 */
function get_cart_items_count() {
  $cart_count = WC()->cart->get_cart_contents_count();

  if (is_numeric($cart_count)) {
    $data = json_encode(array(
      'count' => $cart_count,
    ));
    wp_send_json_success($data);
  } else {
    wp_send_json_error('Ошибка: не получилось загрузить количество товаров в коризне');
  }
}
add_action( 'wp_ajax_nopriv_get_cart_items_count', 'get_cart_items_count' );
add_action( 'wp_ajax_get_cart_items_count', 'get_cart_items_count' );



/**
 * Custom checkout field reciever
 */
function set_custom_address() {
  if (isset($_POST['value'])) {
    // Load settings from woocommerce.php
    extract(custom_address_settings());
    
    if(empty($_POST['value'])) {
      $value = '';
      $label = 'Empty';
    } else {
      $value = $label = esc_attr( $_POST['value'] );
    }
    
    // Update session variable
    WC()->session->set($field_id, $value);
    
    // Send back the data to javascript (json encoded)
    echo $label;
    die();
  }
}
add_action('wp_ajax_custom_address', 'set_custom_address');
add_action('wp_ajax_nopriv_custom_address', 'set_custom_address');