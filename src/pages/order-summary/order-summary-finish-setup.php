<?php
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

session_start();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? null;
if (is_null($user)) {
    header('location: ../login/login.php');
}