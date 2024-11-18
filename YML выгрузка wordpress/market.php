<?php
/* // */
// ADD MARKET SETTINGS PAGE
/* // */
add_action('admin_menu', function() {
	add_menu_page('Настройки Яндекс.Маркета', 'Яндекс.Маркет', 'manage_options', 'yamarket', 'yamarket_setting');
});

function yamarket_setting() {
	?>
	<div class="wrap">
	    <style>
	        form > h2 {
	            margin-top: 30px;
	        }
	        .yamarket-separator {
	            display: block;
	            width: 100%;
	            height: 1px;
	            margin-top: 25px;
	            margin-bottom: 25px;
	            background-color: #ccc;
	        }
	    </style>
	    
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields("yamarket_group");
				do_settings_sections("yamarket_page");
				submit_button();
			?>
		</form>
		
		<div class="yamarket-footnotes">
		    <p>* Срок должен быть указан в пределах от 0 до 60 дней. Например от 3 до 5 суток. Можно указать в От и До одинаковое число, если срок, например, ровно 4 дня.</p>
		    <p>** Например, если срок доставки сегодня, то до указанного часа Яндекс будет отображать, что доставка сегодня, а после указанного часа - завтра. Если срок 5 дней, то до указанного часа будет показываться 5, а после 6.</p>
		</div>
		
		<div class="yamarket-separator"></div>
		
		<div class="yamarket-buttons">
		    <a href="/generate_yamarket_yml.php" class="button button-primary" target="_blank">Сгенерировать YML</a>
		</div>
		
		<script>
		    function updateFieldGroup(group, value) {
		        const priceField = document.querySelector('input[name="global_'+group+'_price"]');
		        const fromField = document.querySelector('input[name="global_'+group+'_from"]');
		        const toField = document.querySelector('input[name="global_'+group+'_to"]');
		        const orderBeforeField = document.querySelector('input[name="global_'+group+'_order_before"]');
		        
		        const groupFields = [priceField, fromField, toField, orderBeforeField];
		        
		        for (const field of groupFields) {
		            if (field) {
		                if (value) {
		                    field.disabled = false;
		                } else {
		                    field.disabled = true;
		                }
		            }
		        }
		    }
		    
            const devileryCheckbox = document.querySelector('input[name="global_delivery"]');
            const pickupCheckbox = document.querySelector('input[name="global_pickup"]');
            
            if (devileryCheckbox !== null) {
                updateFieldGroup('delivery', devileryCheckbox.checked);
                
                devileryCheckbox.addEventListener('change', function() {
                    updateFieldGroup('delivery', devileryCheckbox.checked);
                });
            }
            if (pickupCheckbox !== null) {
                updateFieldGroup('pickup', pickupCheckbox.checked);
                
                pickupCheckbox.addEventListener('change', function() {
                    updateFieldGroup('pickup', pickupCheckbox.checked);
                });
            }
		</script>
	</div>
	<?php

}

add_action('admin_init', 'plugin_settings');
function plugin_settings() {

	// main settings
	$settings_main = array(
		'global_shop' => array('Короткое название магазина', 'text'),
		'global_company' => array('Полное название компании (через ООО, ОАО, ИП, итд)', 'text'),
		'global_vendor' => array('Глобальный производитель всех товаров (не обязательно)', 'text'),
	);
	$settings_delivery = array(
		'global_delivery' => array('Курьерская доставка', 'checkbox'),
		'global_delivery_price' => array('Цена доставки (руб)', 'number_disabled'),
		'global_delivery_from' => array(' ', 'number_row_1_disabled'),
		'global_delivery_to' => array('Срок доставки*', 'number_row_2_disabled'),
		'global_delivery_order_before' => array('После какого часа считать доставку со следующего дня (от 0 до 24)**', 'number_disabled'),
	);
	$settings_pickup = array(
		'global_pickup' => array('Самовывоз', 'checkbox'),
		'global_pickup_price' => array('Цена самовывоза (руб)', 'number_disabled'),
		'global_pickup_from' => array(' ', 'number_row_1_disabled'),
		'global_pickup_to' => array('Срок доставки до пункта выдачи*', 'number_row_2_disabled'),
		'global_pickup_order_before' => array('После какого часа считать самовывоз со следующего дня (от 0 до 24)**', 'number_disabled'),
	);

	// параметры: $option_group, $option_name, $sanitize_callback
	foreach ($settings_main as $slug => $data) {
		register_setting('yamarket_group', $slug);
	}
	foreach ($settings_delivery as $slug => $data) {
		register_setting('yamarket_group', $slug);
	}
	foreach ($settings_pickup as $slug => $data) {
		register_setting('yamarket_group', $slug);
	}

	// параметры: $id, $title, $callback, $page
	add_settings_section('main', 'Основные', '', 'yamarket_page');
	add_settings_section('delivery', 'Доставка', '', 'yamarket_page');
	add_settings_section('pickup', 'Самовывоз', '', 'yamarket_page');

	// параметры: $id, $title, $callback, $page, $section, $args
	foreach ($settings_main as $slug => $data) {
		add_settings_field($slug, $data[0], 'output_'.$data[1].'_option', 'yamarket_page', 'main', array($slug));
	}
	foreach ($settings_delivery as $slug => $data) {
	    if ($data[1] === 'number_row_1_disabled') continue;
		add_settings_field($slug, $data[0], 'output_'.$data[1].'_option', 'yamarket_page', 'delivery', array($slug));
	}
	foreach ($settings_pickup as $slug => $data) {
	    if ($data[1] === 'number_row_1_disabled') continue;
		add_settings_field($slug, $data[0], 'output_'.$data[1].'_option', 'yamarket_page', 'pickup', array($slug));
	}

}

## Заполняем текстовую опцию
function output_text_option($args){
	$val = get_option($args[0]); ?>
	<style>input[type=text] { width: 100%; }</style>
	<input type="text" name="<?=$args[0]?>" value="<?php echo esc_attr( $val ) ?>" /> <?php
}

## Заполняем Да/Нет опцию
function output_checkbox_option($args){
	$val = get_option($args[0]); ?>
	<input type="checkbox" name="<?=$args[0]?>" <?=($val) ? 'checked' : ''?> /> <?php
}

## Заполняем цифровую опцию, изначально заблокированную
function output_number_disabled_option($args){
	$val = get_option($args[0]); ?>
	<style>input[type=number] { width: 60px; }</style>
	<input type="number" name="<?=$args[0]?>" value="<?=$val?>" disabled /> <?php
}

## Заполняем цифровую опцию, изначально заблокированную, в два ряда
function output_number_row_2_disabled_option($args) {
    $slug_from = str_replace('_to', '_from', $args[0]);
    $slug_to = $args[0];
    
	$val_from = get_option($slug_from);
	$val_to = get_option($slug_to); ?>
	
	<span>от</span>
	<input type="number" name="<?=$slug_from?>" value="<?=$val_from?>" disabled />
	<span>до</span>
	<input type="number" name="<?=$slug_to?>" value="<?=$val_to?>" disabled /> 
	<span>дней</span> <?php
}