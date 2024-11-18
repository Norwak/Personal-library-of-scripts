<?php 
define('WP_USE_THEMES', false);
require('./wp-load.php');

function filterXMLString($string) {
    if ($string) {
        $string = htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
        return $string;
    } else {
        return null;
    }
}



// Get required data
$name = filterXMLString(get_option('global_shop'));
$company = filterXMLString(get_option('global_company'));
$vendor = filterXMLString(get_option('global_vendor'));

$delivery = get_option('global_delivery');
if ($delivery) {
    $delivery_price = (int) get_option('global_delivery_price');
    $delivery_from = (int) get_option('global_delivery_from');
    $delivery_to = (int) get_option('global_delivery_to');
    $delivery_order_before = (int) get_option('global_delivery_order_before');
}

$pickup = get_option('global_pickup');
if ($pickup) {
    $pickup_price = (int) get_option('global_pickup_price');
    $pickup_from = (int) get_option('global_pickup_from');
    $pickup_to = (int) get_option('global_pickup_to');
    $pickup_order_before = (int) get_option('global_pickup_order_before');
}

$categories = get_categories();

$wp_products = get_posts(array(
	'numberposts' => -1,
	'post_type'   => 'post',
	'post_status' => 'publish',
));

$products = [];
remove_filter('the_content', 'wpautop');
foreach ($wp_products as $wp_product) {
    $name = filterXMLString(get_the_title($wp_product->ID));
    $local_vendor = filterXMLString(get_field('vendor', $wp_product->ID));
    $url = filterXMLString(get_home_url() . get_the_permalink($wp_product->ID));
    $imageId = get_field('product_image', $wp_product->ID);
    $description = filterXMLString(apply_filters('the_content', get_field('market_description', $wp_product->ID)));
    $diameter = filterXMLString(get_field('product_diameter', $wp_product->ID));
    $pressure = filterXMLString(get_field('product_pressure', $wp_product->ID));
    
    $products[] = [
        'name' => $name,
        'vendor' => ($local_vendor) ? $local_vendor : $vendor,
        'id' => $wp_product->ID,
        'url' => $url,
        'price' => wps_get_retail_price($wp_product->ID),
        'currencyId' => 'RUR',
        'categoryId' => get_the_category($wp_product->ID)[0]->term_id,
        'picture' => ($imageId) ? filterXMLString(wp_get_attachment_image_url($imageId, 'full')) : null,
        'description' => $description,
        'Диаметр' => $diameter,
        'Давление' => $pressure,
    ];
}



// Validate required data
$errors = [];

if (!$name) {
    $errors[] = 'Пожалуйста заполните поле "Название магазина" в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if (!$company) {
    $errors[] = 'Пожалуйста заполните поле "Название компании" в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}

if ($delivery && !$delivery_price) {
    $errors[] = 'Стоимость доставки должна быть заполнена, если доставка включена. Заполните в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($delivery && $delivery_price < 0) {
    $errors[] = 'Стоимость доставки не может быть отрицательная. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($delivery && $delivery_price > 100000) {
    $errors[] = 'Стоимость доставки слишком дорогая ('.$delivery_price.'). Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($delivery && (!$delivery_from || !$delivery_to)) {
    $errors[] = 'Срок доставки должен быть заполнен от и до. Заполните в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($delivery && ($delivery_from < 0 || $delivery_from > 60 || $delivery_to < 0 || $delivery_to > 60)) {
    $errors[] = 'Срок доставки должен быть в пределах от 0 до 60 дней. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($delivery && ($delivery_from > $delivery_to)) {
    $errors[] = 'Минимальный срок доставки указан больше максимального. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($delivery && ($delivery_order_before < 0 || $delivery_order_before > 24 )) {
    $errors[] = 'Час заказа, после которого срок доставки считается со следующего дня, должен быть целым числом в диапазоне от 0 до 24. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}

if ($pickup && !$pickup_price) {
    $errors[] = 'Стоимость доставки в пункт самовывоза должна быть заполнена, если самовывоз включён. Заполните в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($pickup && $pickup_price < 0) {
    $errors[] = 'Стоимость самовывоза не может быть отрицательная. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($pickup && $pickup_price > 100000) {
    $errors[] = 'Стоимость самовывоза слишком дорогая ('.$pickup_price.'). Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($pickup && (!$pickup_from || !$pickup_to)) {
    $errors[] = 'Срок доставки в пункт самовывоза должен быть заполнен от и до. Заполните в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($pickup && ($pickup_from < 0 || $pickup_from > 60 || $pickup_to < 0 || $pickup_to > 60)) {
    $errors[] = 'Срок доставки в пункт самовывоза должен быть в пределах от 0 до 60 дней. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($pickup && ($pickup_from > $pickup_to)) {
    $errors[] = 'Минимальный срок доставки в пункт самовывоза указан больше максимального. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}
if ($pickup && ($pickup_order_before < 0 || $pickup_order_before > 24 )) {
    $errors[] = 'Час заказа, после которого срок доставки в пункт самовывоза считается со следующего дня, должен быть целым числом в диапазоне от 0 до 24. Исправьте  в <a href="/wp-admin/admin.php?page=yamarket" target="_blank">настройках выгрузки</a>';
}

if (count($categories) === 0) {
    $errors[] = 'На сайте нет ни одного товара, выгрузка не будет сгенерирована.';
}

$offers = [];
foreach ($products as $product) {
    if (!$product['name'] || !$product['id'] || !$product['url'] || !$product['price'] || !$product['currencyId'] || !$product['categoryId'] || !$product['picture'] || !$product['description']) {
        continue;
    }
    $offers[] = $product;
}

if (count($offers) === 0) {
    $errors[] = 'На сайте нет подходящих для выгрузки товаров. Проверьте, чтобы у товаров была картинка, краткое описание и розничная цена. Выгрузка не сгенерирована.';
}

if (count($errors)) {
    foreach ($errors as $error) {
        echo '<p>'.$error.'</p>';
    }
    exit();
}



ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<yml_catalog date="<?=date('Y-m-d H:iO')?>">
 <shop>
  <name><?=$name?></name>
  <company><?=$company?></company>
  <url><?=get_home_url()?></url>
  <currencies>
   <currency id="RUR" rate="1"></currency>
  </currencies> <?php
  if (!$delivery) { ?>
    <delivery>false</delivery> <?php
  } else { ?>
    <delivery>true</delivery>
    <delivery-options> 
        <option cost="<?=$delivery_price?>" days="<?=($delivery_from === $delivery_to) ? $delivery_from : $delivery_from.'-'.$delivery_to ?>" <?=($delivery_order_before) ? 'order-before="'.$delivery_order_before.'"' : ''?>/> 
    </delivery-options> <?php
  }
  if (!$pickup) { ?>
    <pickup>false</pickup> <?php
  } else { ?>
    <pickup>true</pickup>
    <pickup-options> 
        <option cost="<?=$pickup_price?>" days="<?=($pickup_from === $pickup_to) ? $pickup_from : $pickup_from.'-'.$pickup_to ?>" <?=($pickup_order_before) ? 'order-before="'.$pickup_order_before.'"' : ''?>/> 
    </pickup-options> <?php
  } ?>
  <categories> <?php
   foreach ($categories as $category) { ?>
    <category id="<?=$category->term_id?>" <?=($category->parent) ? 'parentId="'.$category->parent.'"' : '' ?>><?=$category->name?></category> <?php
   } ?>
  </categories>
  <offers> <?php
   foreach ($offers as $offer) { ?>
       <offer id="<?=$offer['id']?>" available="true">
        <url><?=$offer['url']?></url>
        <price><?=$offer['price']?></price>
        <currencyId><?=$offer['currencyId']?></currencyId>
        <categoryId><?=$offer['categoryId']?></categoryId>
        <name><?=$offer['name']?></name>
        <picture><?=$offer['picture']?></picture>
        <description><?=$offer['description']?></description>
        <?=($offer['vendor']) ? '<vendor>'.$offer['vendor'].'</vendor>' : ''?>
        <?=($offer['diameter']) ? '<param name="Диаметр">'.$offer['diameter'].'</param>' : ''?>
        <?=($offer['pressure']) ? '<param name="Давление">'.$offer['pressure'].'</param>' : ''?>
       </offer> <?php
   } ?>
  </offers>
 </shop>
</yml_catalog><?php

$content = ob_get_clean();

$file_write = file_put_contents(dirname(__FILE__) . '/wp-content/uploads/yandex.yml', $content);

if ($file_write) {
    echo 'YML выгрузка создана успешно по ссылке <a href="/wp-content/uploads/yandex.yml" target="_blank">здесь</a>';
} else {
    echo 'При записи файла возникла ошибка: возможно файл уже используется или место закончилось';
}