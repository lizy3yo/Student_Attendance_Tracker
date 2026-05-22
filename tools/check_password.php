<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->prepare('select password from users where email=?');
$stmt->execute(['de.jesus.kharl@gordoncollege.edu.ph']);
$hash = $stmt->fetchColumn();
if (!$hash) {
    echo "no-hash\n";
    exit(1);
}
echo (password_verify('TestPass123', $hash) ? "ok\n" : "fail\n");
