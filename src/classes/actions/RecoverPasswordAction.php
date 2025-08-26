<?php

include_once '../abstracts/Entity.php';
include_once '../../classes/exceptions/NoSuchUserException.php';
include_once '../RecoveryPassword.php';
include_once '../enums/CrudEnum.php';
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

    try {
        $loginService->recoverPassword($email);
        header("Location: ../../pages/forgot_password/forgot_password.php?status=".ConstUtils::FORGOT_PASSWORD_STATUS_SUCCESS);
    } catch (NoSuchUserException $e) {
        header("Location: ../../pages/forgot_password/forgot_password.php?status=".ConstUtils::FORGOT_PASSWORD_STATUS_ERROR_NO_USER);
    } catch (Exception $e) {
        header("Location: ../../pages/forgot_password/forgot_password.php?status=".ConstUtils::FORGOT_PASSWORD_STATUS_ERROR);
    }
}
