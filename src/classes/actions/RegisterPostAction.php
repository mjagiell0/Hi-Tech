<?php

include_once '../abstracts/Entity.php';
include_once '../../classes/exceptions/NoSuchUserException.php';
include_once '../enums/CrudEnum.php';
include_once '../../classes/exceptions/PasswordMismatchException.php';
include_once '../../classes/exceptions/EmailInUseException.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/User.php';
include_once '../../classes/DatabaseHandler.php';
include_once '../../classes/Services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();
session_start();

$firstName = $_POST[ConstUtils::FIELD_LABEL_FIRSTNAME];
$lastName = $_POST[ConstUtils::FIELD_LABEL_LASTNAME];
$email = $_POST[COnstUtils::FIELD_LABEL_EMAIL];
$password = $_POST[ConstUtils::FIELD_LABEL_PASSWORD];

$loginService = new LoginService();

try {
    $loginService->register($firstName, $lastName, $email, $password);
    header('Location: ../../pages/register/register.php?status=success');
} catch (EmailInUseException $e) {
    echo $e->getMessage();
    exit();
}