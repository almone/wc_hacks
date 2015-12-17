<?php
// Post-formats support
add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

// Decide when you want to apply the auto paragraph
remove_filter('the_content','wpautop');
add_filter('the_content','custom_formatting');

function custom_formatting($content){
if(is_page('home'))
    return $content; //no autop
else
    return wpautop($content);
}

// Hide the 'Free!' price notice
add_filter( 'woocommerce_variable_free_price_html',  'hide_free_price_notice' );
add_filter( 'woocommerce_free_price_html',           'hide_free_price_notice' );
add_filter( 'woocommerce_variation_free_price_html', 'hide_free_price_notice' );

function hide_free_price_notice( $price ) {
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    return '<a href="skype:rennsport-rus?chat" class="button etheme_add_to_cart_button product_type_simple">Предзаказ по скайпу</a>';
}

// Добавляем значение сэкономленных процентов рядом с ценой у товаров
add_filter( 'woocommerce_sale_price_html', 'woocommerce_custom_sales_price', 10, 2 );
function woocommerce_custom_sales_price( $price, $product ) {
$percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
return $price . '<span class="percentage-save">' . sprintf( __(' (скидка %s)', 'woocommerce' ), $percentage . '%' ) . '</span>';
}

// Tags
add_shortcode('shometags', 'get_procuctTags');
function get_procuctTags() {
    $tagTerms = get_terms( 'product_tag', array(
        'hide_empty' => 0,
        'orderby' => 'count',
        'order' => 'DESC'
    ));

    if ($tagTerms) {
        echo '<ul class="grid cs-style-5 tags">';
        foreach ($tagTerms as $tagTerm) {
            echo '<li><a title="Перейти" href="/' . $tagTerm->slug . '"><figure>' . $tagTerm->description . '<figcaption><h3> ' . $tagTerm->name . '</h3></figcaption></figure></a></li>';
        }
        echo '</ul>';
    }
}

// Circles (WC Categories)
add_shortcode('shomecats', 'get_cats');
function get_cats() {
    $catTerms = get_terms('product_cat', array(
        'hide_empty' => 0,
        'include' => '1422, 1344, 984, 1446, 1000, 1050, 996, 975, 987, 1016, 994, 564, 1186, 1048, 654, 1135, 645, 1445'
    ));
    $ch_n = 0;
    if ($catTerms) {
        echo '<ul class="ch-grid">';
        foreach($catTerms as $catTerm) :
            $thumbnail_id = get_woocommerce_term_meta( $catTerm->term_id, 'thumbnail_id', true );
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
            $ch_n++;
            echo '<li><a title="Перейти" href="/products/'.$catTerm->slug.'"><div class="ch-item" style="background-image: url('.$image.'); background-size: 80%; background-repeat: no-repeat; background-color: white;"><div class="ch-info"><h3>'.$catTerm->name.'</h3></div></div></a></li>';
        endforeach;
        wp_reset_query();
        echo '</ul>';
    }
}

// Page load in seconds (DEBUG)
add_action('shutdown', __NAMESPACE__ . '\\time_elapsed');
function time_elapsed() {
  $time_elapsed = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']; // PHP 5.4.0
  echo $time_elapsed, PHP_EOL;
}