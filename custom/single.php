<?php
$post = $wp_query->post;
if (in_category(7777) || post_is_in_descendant_category(7777)) {
  include(TEMPLATEPATH . '/single-produkcija.php');
} else {
  include(TEMPLATEPATH . '/single-default.php');
}?>