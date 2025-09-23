<?php
include_once '../../classes/utils/ConstUtils.php';
session_start();
if (isset($_SESSION[ConstUtils::SESSION_USER_CART])) {
    $cart = $_SESSION[ConstUtils::SESSION_USER_CART];

    foreach ($cart as $item) {
        echo 'Produkt ID: ' . htmlspecialchars($item['product_id']) . '<br>';
        echo 'Ilość: ' . htmlspecialchars($item['quantity']) . '<br><br>';
    }
} else {
    echo 'fiutt';
}
