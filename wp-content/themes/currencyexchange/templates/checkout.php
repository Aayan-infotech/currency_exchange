<?php
// Template Name: Order Status
get_header();
$key_secret = get_option('stripe_live_key_secret');

use \Stripe\Stripe;
use \Stripe\Checkout\Session;

require_once get_template_directory() . '/stripe/vendor/autoload.php';
\Stripe\Stripe::setApiKey($key_secret);
$order_id   = intval($_GET['order_id']);
$order_id   = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$session_id = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';

if (empty($session_id)) {
    update_post_meta($order_id, 'status', 'Failed');
}

$status      = 'failed';
$paid_amount = 0;
$currency    = '';
$transaction = '';
$customer_email = '';
if ($order_id && !empty($session_id)) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        if ($session->payment_status === 'paid') {
            $status        = 'success';
            $paid_amount   = $session->amount_total / 100;
            $currency      = strtoupper($session->currency);
            $transaction   = $session->payment_intent;
            $customer_email = $session->customer_email ?? '';
            update_post_meta($order_id, 'status', 'Processing');
            update_post_meta($order_id, 'transaction_id', $transaction);
            update_post_meta($order_id, 'paid_amount', $paid_amount);
            update_post_meta($order_id, 'currency', $currency);
            update_post_meta($order_id, 'customer_email', $customer_email);
        } else {
            update_post_meta($order_id, 'status', 'Failed');
        }
    } catch (\Exception $e) {
        $status = 'error';
        $error_message = $e->getMessage();
    }
}

$status_classes = [
    'success' => ['bg' => 'bg-success', 'text' => 'text-white', 'message' => 'Payment Successful!'],
    'failed'  => ['bg' => 'bg-danger',  'text' => 'text-white', 'message' => 'Payment Failed!'],
    'error'   => ['bg' => 'bg-warning', 'text' => 'text-dark',  'message' => 'Error Occurred!'],
];
$card_class = $status_classes[$status] ?? $status_classes['error'];
?>
<section class="main-sections">
    <div class="container my-5">
        <div class="card <?php echo esc_attr($card_class['bg'] . ' ' . $card_class['text']); ?>">
            <div class="card-body">
                <h2 class="card-title"><?php echo esc_html($card_class['message']); ?></h2>
                <hr class="my-3">
                <?php if ($status === 'success') : ?>
                    <p><strong>Order ID:</strong> <?php echo esc_html($order_id); ?></p>
                    <p><strong>Transaction ID:</strong> <?php echo esc_html($transaction); ?></p>
                    <p><strong>Paid Amount:</strong> <?php echo esc_html(number_format($paid_amount, 2)); ?> <?php echo esc_html($currency); ?></p>
                <?php elseif ($status === 'failed') : ?>
                    <p>Unfortunately, your payment was not successful. Please try again.</p>
                <?php elseif ($status === 'error') : ?>
                    <p>Stripe verification failed: <?php echo esc_html($error_message ?? 'Unknown error'); ?></p>
                <?php endif; ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-light mt-3">Back to Home</a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>