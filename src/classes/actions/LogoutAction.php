<?php
include_once '../../classes/utils/ConstUtils.php';

if ($_SERVER[ConstUtils::REQUEST_METHOD] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';

    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();

    $loginService = new LoginService();

    if (isset($_SESSION[ConstUtils::SESSION_USER])) {
        LoginService::logout();
    }
    $url = $_SESSION[ConstUtils::PREV_PAGE];
    header("Location: $url");
}