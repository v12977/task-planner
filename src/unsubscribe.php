<?php
require_once __DIR__ . '/functions.php';

$email = $_GET['email'] ?? '';
unsubscribeEmail($email);
echo "<p>You have been unsubscribed. Sorry to see you go!</p>";
