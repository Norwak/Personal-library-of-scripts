<?
function getAllProductImages(){
global $product;
$id = $product->get_id();
$products = wc_get_product( $id );
$tempArr = [];

$product_obj = new stdClass();
$images_ids = $products->get_gallery_attachment_ids();

$images_arr = [];
for($i = 0, $j = count($images_ids); $i < $j;$i++ ){
    $image_query = wp_get_attachment_image_src($images_ids[$i]);
    $image_query_full = wp_get_attachment_image_src($images_ids[$i], 'full');
    $img = new StdClass;
    $img->src = $image_query[0];
    $img->src_full = $image_query_full[0];
    array_push($images_arr, $img);
}
$product_obj = $images_arr;

return $product_obj;
}
