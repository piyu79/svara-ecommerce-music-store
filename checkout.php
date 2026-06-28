<?php
require_once 'razorpay_config.php';

$host = "localhost";
$username = "root";
$db_password = "";
$dbname = "priyaa1";

function parseCartPrice($priceText) {
    $cleaned = preg_replace('/[^0-9.]/', '', (string)$priceText);
    return is_numeric($cleaned) ? (float)$cleaned : 0;
}

function createRazorpayOrder($amountPaise, $receipt, $keyId, $keySecret) {
    if (!function_exists('curl_init')) {
        return ['success' => false, 'message' => 'cURL is not enabled on this PHP setup.'];
    }

    $payload = json_encode([
        'amount' => $amountPaise,
        'currency' => 'INR',
        'receipt' => $receipt
    ]);

    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_USERPWD => $keyId . ':' . $keySecret,
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        return ['success' => false, 'message' => 'Razorpay order request failed: ' . $curlError];
    }

    $data = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300 && !empty($data['id'])) {
        return ['success' => true, 'order' => $data];
    }

    $message = isset($data['error']['description']) ? $data['error']['description'] : 'Could not create Razorpay order.';
    return ['success' => false, 'message' => $message];
}

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$cartItems = [];
$totalAmount = 0;
$pageError = '';
$setupWarning = '';
$orderId = '';
$amountPaise = 0;

if ($userId <= 0) {
    $pageError = 'Missing user information. Please go back to your cart and try again.';
} else {
    $con = mysqli_connect($host, $username, $db_password, $dbname);

    if (!$con) {
        $pageError = 'Database connection failed: ' . mysqli_connect_error();
    } else {
        $stmt = mysqli_prepare($con, "SELECT id, product_name, product_price, product_image, quantity FROM cart WHERE user_id = ? ORDER BY added_at DESC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $rowTotal = parseCartPrice($row['product_price']) * (int)$row['quantity'];
            $row['line_total'] = $rowTotal;
            $cartItems[] = $row;
            $totalAmount += $rowTotal;
        }

        mysqli_close($con);

        if (count($cartItems) === 0) {
            $pageError = 'Your cart is empty. Add a few instruments before checkout.';
        } else {
            $amountPaise = (int)round($totalAmount * 100);

            if (!razorpayKeysConfigured($razorpayKeyId, $razorpayKeySecret)) {
                $setupWarning = 'Update razorpay_config.php with your Razorpay Key ID and Key Secret before making a test payment.';
            } else {
                $receipt = 'svara_' . $userId . '_' . time();
                $orderResult = createRazorpayOrder($amountPaise, $receipt, $razorpayKeyId, $razorpayKeySecret);

                if ($orderResult['success']) {
                    $orderId = $orderResult['order']['id'];
                } else {
                    $pageError = $orderResult['message'];
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SVARA</title>
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
            background: #F7F5F2;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background-color: #121212;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .header-links {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .header-links a {
            color: white;
            text-decoration: none;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            font-weight: 600;
        }

        .checkout-wrapper {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.5rem 2rem;
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 1.5rem;
        }

        .panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
            padding: 1.5rem;
        }

        .panel h1,
        .panel h2 {
            font-family: 'Playfair Display', serif;
            color: #121212;
            margin-bottom: 1rem;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .summary-item {
            display: grid;
            grid-template-columns: 78px 1fr auto;
            gap: 1rem;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ececec;
        }

        .summary-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .summary-item img {
            width: 78px;
            height: 78px;
            object-fit: contain;
            background: #f8f8f8;
            border-radius: 10px;
            padding: 0.35rem;
        }

        .item-name {
            font-weight: 700;
            color: #121212;
        }

        .item-meta {
            color: #666;
            font-size: 0.94rem;
        }

        .item-total {
            font-weight: 700;
            color: #96281B;
            white-space: nowrap;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.05rem;
            margin-bottom: 0.8rem;
        }

        .grand-total {
            font-size: 1.45rem;
            font-weight: 800;
            color: #121212;
        }

        .helper-text {
            color: #5f6b76;
            margin-bottom: 1rem;
        }

        .notice,
        .error-box {
            border-radius: 12px;
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .notice {
            background: #fff6db;
            color: #876800;
            border: 1px solid #f1df9b;
        }

        .error-box {
            background: #fdeeee;
            color: #a23030;
            border: 1px solid #f3c1c1;
        }

        .pay-button {
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 0.95rem 1.2rem;
            background: #96281B;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .pay-button:hover {
            background: #B03020;
            transform: translateY(-1px);
        }

        .pay-button:disabled {
            background: #b7bcc2;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #121212;
            font-weight: 700;
            text-decoration: none;
        }

        .payment-status {
            margin-top: 1rem;
            color: #5f6b76;
            min-height: 1.4rem;
        }

        @media (max-width: 900px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">SVAR<span style="color:#96281B;">A</span></div>
        <div class="header-links">
            <a href="home1.html">Back to Cart</a>
            <a href="guitars1.html">Continue Shopping</a>
        </div>
    </header>

    <div class="checkout-wrapper">
        <section class="panel">
            <h1>Checkout</h1>
            <p class="helper-text">Review the items below, then open the Razorpay popup to complete your test payment.</p>

            <?php if ($pageError !== ''): ?>
                <div class="error-box"><?php echo htmlspecialchars($pageError, ENT_QUOTES, 'UTF-8'); ?></div>
                <a class="back-link" href="home1.html">← Return to cart</a>
            <?php else: ?>
                <div class="summary-list">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="summary-item">
                            <img src="<?php echo htmlspecialchars($item['product_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
                            <div>
                                <div class="item-name"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="item-meta">Qty: <?php echo (int)$item['quantity']; ?> • <?php echo htmlspecialchars($item['product_price'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                            <div class="item-total">₹<?php echo number_format($item['line_total'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <aside class="panel">
            <h2>Payment Summary</h2>

            <?php if ($setupWarning !== ''): ?>
                <div class="notice"><?php echo htmlspecialchars($setupWarning, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if ($pageError === ''): ?>
                <div class="total-line">
                    <span>Items</span>
                    <span><?php echo count($cartItems); ?></span>
                </div>
                <div class="total-line">
                    <span>Amount</span>
                    <span class="grand-total">₹<?php echo number_format($totalAmount, 2); ?></span>
                </div>
                <div style="background:#FDF6F5;border:1.5px solid #96281B;border-radius:12px;padding:1rem 1.1rem;margin-bottom:1rem;">
                    <div style="font-weight:700;color:#96281B;margin-bottom:0.6rem;">🧪 Demo Mode — Use These Test Credentials</div>
                    <div style="font-size:0.86rem;color:#333;line-height:1.8;">

                        <div style="background:white;border-radius:8px;padding:0.6rem 0.75rem;margin-bottom:0.5rem;border:1px solid #e8e0de;">
                            💳 <b>Card (Visa — works in test mode):</b><br>
                            Number: <code style="background:#f0ece9;padding:0.1rem 0.4rem;border-radius:4px;">5267 3181 8797 5449</code><br>
                            Expiry: <code style="background:#f0ece9;padding:0.1rem 0.4rem;border-radius:4px;">02/35</code> &nbsp;
                            CVV: <code style="background:#f0ece9;padding:0.1rem 0.4rem;border-radius:4px;">123</code><br>
                            OTP: <code style="background:#f0ece9;padding:0.1rem 0.4rem;border-radius:4px;">1234</code> &nbsp;
                            <span style="color:#555;">(enter when prompted)</span>
                        </div>

                        <div style="background:white;border-radius:8px;padding:0.6rem 0.75rem;border:1px solid #e8e0de;">
                            🏦 <b>Netbanking:</b> Pick any bank → use <b>any username &amp; password</b>
                        </div>

                        <div style="margin-top:0.5rem;font-size:0.78rem;color:#777;">
                            ⚠️ UPI &amp; international cards are not supported in Razorpay test mode.
                        </div>
                    </div>
                    <div style="margin-top:0.6rem;font-size:0.8rem;color:#96281B;font-weight:600;">✅ No real money is charged — this is test mode only.</div>
                </div>
                <p class="helper-text">Only the Razorpay Key ID is exposed to the checkout popup. Your Key Secret stays on the server and is used in payment verification.</p>
                <button
                    class="pay-button"
                    id="payButton"
                    <?php echo ($setupWarning !== '' || $orderId === '') ? 'disabled' : ''; ?>
                >
                    Pay with Razorpay
                </button>
                <div class="payment-status" id="paymentStatus"></div>
                <a class="back-link" href="home1.html">← Edit cart items</a>
            <?php endif; ?>
        </aside>
    </div>

    <?php if ($pageError === '' && $setupWarning === '' && $orderId !== ''): ?>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            document.getElementById('payButton').addEventListener('click', function () {
                const statusEl = document.getElementById('paymentStatus');
                statusEl.textContent = 'Opening Razorpay...';

                const options = {
                    key: <?php echo json_encode($razorpayKeyId); ?>,
                    amount: <?php echo json_encode($amountPaise); ?>,
                    currency: 'INR',
                    name: 'SVARA',
                    description: 'Instrument checkout',
                    order_id: <?php echo json_encode($orderId); ?>,
                    handler: function (response) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'verify_payment.php';

                        [
                            ['razorpay_payment_id', response.razorpay_payment_id],
                            ['razorpay_order_id', response.razorpay_order_id],
                            ['razorpay_signature', response.razorpay_signature],
                            ['user_id', <?php echo json_encode($userId); ?>],
                            ['amount', <?php echo json_encode(number_format($totalAmount, 2, '.', '')); ?>]
                        ].forEach(function (field) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = field[0];
                            input.value = field[1];
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    },
                    modal: {
                        ondismiss: function () {
                            statusEl.textContent = 'Payment popup closed. Your cart is still unchanged.';
                        }
                    },
                    theme: {
                        color: '#96281B'
                    }
                };

                const razorpay = new Razorpay(options);
                razorpay.on('payment.failed', function (response) {
                    const reason = response.error && response.error.description ? response.error.description : 'Payment failed. Please try again.';
                    statusEl.textContent = reason;
                });
                razorpay.open();
            });
        </script>
    <?php endif; ?>
</body>
</html>