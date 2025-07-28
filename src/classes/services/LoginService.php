<?php

class LoginService
{
    public static function login($email, $password)
    {
        $dbHandler = new DatabaseHandler(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );

        $user = $dbHandler->query(new User(), $email);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                // Set session variables or perform other login actions
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_email'] = $user->getEmail();
                return $user;
            } else {
                throw new PasswordMismatchException();
            }
        } else {
            throw new NoSuchUserException();
        }
    }
}
