<?php
include_once '../utils/ConstUtils.php';
if ($_SERVER['REQUEST_METHOD'] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../enums/OrderStatusEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/entities/PaymentCard.php';
    include_once '../../classes/entities/Address.php';
    include_once '../../classes/entities/Order.php';
    include_once '../../classes/entities/Product.php';
    include_once '../../classes/entities/OrderItem.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    include_once '../../classes/Services/ProductService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();

    $status = ConstUtils::STATUS_ORDER_COMPLETE_SUCCESS;
    $cart = ProductService::getCartProducts();
    $address = $_SESSION[ConstUtils::ORDER_ADDRESS];
    $paymentCard = $_SESSION[ConstUtils::ORDER_PAYMENT_CARD];
    $user = $_SESSION[ConstUtils::SESSION_USER];

    try {
        $databaseHandler = DatabaseHandler::getDbHandler();
        $databaseHandler->query(
            new Order(),
            CrudEnum::CREATE,
            $user->getId(),
            $address->getId(),
            $paymentCard->getId(),
            OrderStatusEnum::PENDING->value,
        );
        $order = $databaseHandler->query(new Order(), CrudEnum::READ, $user->getId());
        if (is_array($order)) {
            $order = $order[0];
        }

        foreach ($cart as $product) {
            $databaseHandler->query(new OrderItem(), CrudEnum::CREATE, $product->getId(), $product->getQuantity(), $order->getId());
            echo '<br>'.$product->getId();
            echo '<br>'.$user->getId();
            $databaseHandler->query(new CartProduct(), CrudEnum::DELETE, $product->getId(), $user->getId());
        }
    } catch (Exception $e) {
        $status = ConstUtils::STATUS_ERROR.'&message='.$e->getMessage();
    }

    header("Location: ../../pages/order-summary/order-summary-finish.php?status=". $status);
}


