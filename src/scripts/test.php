<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page</title>
</head>

<body>
    <?php

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // Ścieżka do katalogu z .env
    $dotenv->load();

    $dbHandler = new DatabaseHandler(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );
    $user = $dbHandler->query(new User(), 'admin@admin.pl');
    if ($user) {
        echo "User found: " . $user;
    } else {
        echo "No user found.";
    }
    ?>
</body>

</html>
