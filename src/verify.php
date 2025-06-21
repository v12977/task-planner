
<?php
require_once __DIR__ . '/functions.php';

$email = $_GET['email'] ?? '';
$code  = $_GET['code']  ?? '';

$isVerified = verifySubscription($email, $code);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Verification Status</title>
</head>
<body>
  <h2>
    <?php
    if ($isVerified) {
        echo "✅ Subscription Verified!";
    } else {
        echo "❌ Invalid or Expired Verification Link.";
    }
    ?>
  </h2>
  <a href="/index.php">Go Back to Home</a>
</body>
</html>
