<?php

include_once '../abstracts/Entity.php';
include_once '../../classes/exceptions/NoSuchUserException.php';
include_once '../enums/CrudEnum.php';
include_once '../../classes/exceptions/PasswordMismatchException.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/User.php';
include_once '../../classes/DatabaseHandler.php';
include_once '../../classes/Services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();
session_start();

$password = $_POST[ConstUtils::FIELD_LABEL_PASSWORD];
$userId = $_POST[ConstUtils::FIELD_LABEL_USER_ID];
$token = $_POST[ConstUtils::FIELD_LABEL_RECOVERY_TOKEN];

$loginService = new LoginService();

try {
    $loginService->resetPassword($userId, $password);
    header("Location: ../../pages/reset_password/reset_password.php?token=".$token."&status=".ConstUtils::FORGOT_PASSWORD_STATUS_SUCCESS);
} catch (Exception $e) {
    header("Location: ../../pages/reset_password/reset_password.php?token=".$token."&status=".ConstUtils::FORGOT_PASSWORD_STATUS_ERROR);
}
