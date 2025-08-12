<?php

include_once '../../interfaces/Querable.php';
include_once '../../classes/exceptions/NoSuchUserException.php';
include_once '../../classes/exceptions/PasswordMismatchException.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/User.php';
include_once '../../classes/DatabaseHandler.php';
include_once '../../classes/Services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();
session_start();

$loginService = new LoginService();

if ($_SERVER[ConstUtils::REQUEST_METHOD] === ConstUtils::POST_METHOD) {
    $email = $_POST[ConstUtils::FIELD_LABEL_EMAIL];
    $password = $_POST[ConstUtils::FIELD_LABEL_PASSWORD];

    $user = $loginService->login($email, $password);
}
