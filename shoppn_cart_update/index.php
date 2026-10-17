<?php

/*
 * Front door of the shop. Everything customers see goes through here:
 *   index.php                 -> home.php (featured products)
 *   index.php?cat=N           -> products in a category
 *   index.php?brand=N         -> products of a brand
 *   index.php?pro_id=N        -> single product page
 *   index.php?user_query=...  -> search results
 *   index.php?add_cart=N      -> add to cart, then back
 */

if (isset($_GET['add_cart'])) {
    require __DIR__ . '/actions/add_to_cart_action.php';
    exit;
}

if (isset($_GET['pro_id'])) {
    require __DIR__ . '/views/single_product.php';
    exit;
}

if (isset($_GET['user_query'])) {
    require __DIR__ . '/views/search_results.php';
    exit;
}

require __DIR__ . '/views/home.php';
