<?php
require_once 'razorpay_config.php';

$host = "localhost";
$username = "root";
$db_password = "";
$dbname = "priyaa1";

$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$paymentId = isset($_POST['razorpay_payment_id']) ? trim($_POST['razorpay_payment_id']) : '';
$orderId = isset($_POST['razorpay_order_id']) ? trim($_POST['razorpay_order_id']) : '';
$signature = isset($_POST['razorpay_signature']) ? trim($_POST['razorpay_signature']) : '';
$amount = isset($_POST['amount']) ? trim($_POST['amount']) : '0.00';

$status = 'failed';
$title = 'Payment could not be verified';
$message = 'Something went wrong while verifying the payment.';
$details = '';

if (!razorpayKeysConfigured($razorpayKeyId, $razorpayKeySecret)) {
    $message = 'Update razorpay_config.php with your Razorpay Key ID and Key Secret before verifying payments.';
} elseif ($userId <= 0 || $paymentId === '' || $orderId === '' || $signature === '') {
    $message = 'Missing payment details. Please try the checkout process again.';
} else {
    $generatedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $razorpayKeySecret);

    if (hash_equals($generatedSignature, $signature)) {
        $status = 'success';
        $title = 'Payment verified successfully';
        $message = 'Your Razorpay payment signature matched successfully.';
        $details = 'Payment ID: ' . $paymentId . ' • Order ID: ' . $orderId;

        $con = mysqli_connect($host, $username, $db_password, $dbname);
        if ($con) {
            $stmt = mysqli_prepare($con, "DELETE FROM cart WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_close($con);
        }
    } else {
        $message = 'The Razorpay signature did not match. No cart items were removed.';
        $details = 'Please retry the payment from checkout.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification - SVARA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .status-card {
            width: 100%;
            max-width: 640px;
            background: white;
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .status-badge {
            width: 78px;
            height: 78px;
            margin: 0 auto 1.1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
        }

        .status-success {
            background: #e9f8ef;
            color: #219150;
        }

        .status-failed {
            background: #fdeeee;
            color: #c0392b;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
            margin-bottom: 0.8rem;
        }

        p {
            color: #5f6b76;
            margin-bottom: 0.8rem;
        }

        .amount {
            font-size: 1.2rem;
            font-weight: 800;
            color: #2c3e50;
        }

        .actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .actions a {
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .primary-link {
            background: #2c3e50;
            color: white;
        }

        .secondary-link {
            background: #eef2f5;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="status-card">
        <div class="status-badge <?php echo $status === 'success' ? 'status-success' : 'status-failed'; ?>">
            <?php echo $status === 'success' ? '✓' : '!'; ?>
        </div>
        <h1><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php if ($amount !== ''): ?>
            <p class="amount">Amount: ₹<?php echo htmlspecialchars($amount, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if ($details !== ''): ?>
            <p><?php echo htmlspecialchars($details, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <div class="actions">
            <a class="primary-link" href="home1.html">Back to Home</a>
            <a class="secondary-link" href="checkout.php?user_id=<?php echo (int)$userId; ?>">Return to Checkout</a>
        </div>
    </div>
</body>
</html>
