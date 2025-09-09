<?php

include_once '../abstracts/Entity.php';
include_once '../../classes/exceptions/NoSuchUserException.php';
include_once '../enums/CrudEnum.php';
include_once '../../classes/exceptions/PasswordMismatchException.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/entities/User.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/Services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();
session_start();

$loginService = new LoginService();

if ($_SERVER[ConstUtils::REQUEST_METHOD] === ConstUtils::POST_METHOD) {
    $email = $_POST[ConstUtils::FIELD_LABEL_EMAIL];
    $password = $_POST[ConstUtils::FIELD_LABEL_PASSWORD];

    $loginService->login($email, $password);

    if ($_SESSION[ConstUtils::SESSION_USER]) {
        header('Location: ../../pages/login/test_success.php');
    }
}
