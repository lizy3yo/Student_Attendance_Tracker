<?php
$hash = password_hash('TestPass123', PASSWORD_BCRYPT);
$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->prepare('update users set password=? where email=?');
$stmt->execute([$hash, 'de.jesus.kharl@gordoncollege.edu.ph']);
echo "updated\n";
