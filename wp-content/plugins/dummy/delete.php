<?php

delete();

/**
 * delete
 */
function delete()
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
        // delete product
        if (wc_get_products(['sku' => $product_type])) {
            wc_get_products(['sku' => $product_type])[0]->delete(true);
        }

        // delete category
        if (get_term_by('slug', $product_type, 'product_cat')) {
            wp_delete_term(get_term_by('slug', $product_type, 'product_cat')->term_id, 'product_cat');
        }

        // delete tag
        if (get_term_by('slug', $product_type, 'product_tag')) {
            wp_delete_term(get_term_by('slug', $product_type, 'product_tag')->term_id, 'product_tag');
        }
    }
}
