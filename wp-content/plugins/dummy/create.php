<?php

create();

/**
 * create
 */
function create()
{
    $product_types = [
        'simple',
        'virtual',
        'downloadable',
        'grouped',
        'external',
        'variable',
    ];

    foreach ($product_types as $product_type) {
        // create category
        wp_insert_term(ucwords($product_type), 'product_cat');

        // create tag
        wp_insert_term(ucwords($product_type), 'product_tag');

        // create simple product
        if ($product_type == 'simple' && !wc_get_products(['sku' => $product_type])) {
            $product = new WC_Product_Simple();
            shared($product, $product_type);
        }

        // create virtual product
        if ($product_type == 'virtual' && !wc_get_products(['sku' => $product_type])) {
            $product = new WC_Product_Simple();
            $product->set_virtual(true);
            shared($product, $product_type);
        }

        // create downloadable product
        if ($product_type == 'downloadable' && !wc_get_products(['sku' => $product_type])) {
            $product = new WC_Product_Simple();
            $download = new WC_Product_Download();
            $file_url = wp_get_attachment_url(5);
            $download->set_name(basename($file_url));
            $download->set_id(md5($file_url));
            $download->set_file($file_url);
            $downloads[] = $download;
            $product->set_downloads($downloads);
            $product->set_download_limit(1);
            $product->set_download_expiry(7);
            $product->set_downloadable(true);
            shared($product, $product_type);
        }

        // create grouped product
        if ($product_type == 'grouped' && !wc_get_products(['sku' => $product_type])) {
            $product = new WC_Product_Grouped();
            $product->set_children([33, 34, 35]);
            shared($product, $product_type);
        }

        // create external product
        if ($product_type == 'external' && !wc_get_products(['sku' => $product_type])) {
            $product = new WC_Product_External();
            $product->set_product_url('https://woocommerce.com');
            $product->set_button_text('CUSTOM BUTTON TEXT');
            shared($product, $product_type);
        }

        // create variable product
        if ($product_type == 'variable' && !wc_get_products(['sku' => $product_type])) {
            $product = new WC_Product_Variable();
            shared($product, $product_type);

            // attributes
            $color = new WC_Product_Attribute();
            $color->set_name('Color');
            $color->set_options(['Red', 'Green', 'Blue']);
            $color->set_position(0);
            $color->set_visible(true);
            $color->set_variation(true);

            $size = new WC_Product_Attribute();
            $size->set_name('Size');
            $size->set_options(['Small', 'Medium', 'Large']);
            $size->set_position(1);
            $size->set_visible(true);
            $size->set_variation(true);

            $product->set_attributes([$color, $size]);

            $product->save();

            // variations
            $attributes = [
                ['color' => 'Red', 'size' => 'Small'],
                ['color' => 'Red', 'size' => 'Medium'],
                ['color' => 'Red', 'size' => 'Large'],
                ['color' => 'Green', 'size' => 'Small'],
                ['color' => 'Green', 'size' => 'Medium'],
                ['color' => 'Green', 'size' => 'Large'],
                ['color' => 'Blue', 'size' => 'Small'],
                ['color' => 'Blue', 'size' => 'Medium'],
                ['color' => 'Blue', 'size' => 'Large'],
            ];

            foreach ($attributes as $attribute) {
                $variation = new WC_Product_Variation();
                $variation->set_parent_id($product->get_id());
                $variation->set_sku(uniqid());
                $variation->set_attributes($attribute);
                $variation->set_regular_price(rand(1, 100));
                $variation->save();
            }
        }
    }
}

/**
 * get_image_id_by_name
 */
function get_image_id_by_name($name)
{
    $id = '';

    $the_query = new WP_Query([
        'posts_per_page' => 1,
        'post_status' => 'any',
        'post_type'   => 'attachment',
        'name' => $name,
    ]);

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $id = get_the_ID();
        }
    } else {
        return 'file not found';
    }

    wp_reset_postdata();

    return $id;
}

/**
 * shared
 */
function shared($product, $product_type)
{
    $product->set_name(ucwords($product_type) . ' Product');
    $product->set_slug(strtolower($product_type) . '-product');
    $product->set_description('It\'s a ' . $product_type . ' product.');
    $product->set_short_description('It\'s a ' . $product_type . ' product.');
    $product->set_regular_price(rand(1, 100));
    $product->set_sku(strtoupper($product_type));
    $product->set_featured(true);
    $product->set_category_ids([get_term_by('slug', $product_type, 'product_cat')->term_id]);
    $product->set_tag_ids([get_term_by('slug', $product_type, 'product_tag')->term_id]);
    $product->set_image_id(get_image_id_by_name('woocommerce-placeholder'));
    $product->set_gallery_image_ids([get_image_id_by_name('woocommerce-placeholder')]);
    $product->save();
}
