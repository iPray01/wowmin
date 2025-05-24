<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .receipt-number {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c5282;
        }
        .details {
            border: 1px solid #eee;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
            border-top: 2px solid #eee;
            padding-top: 20px;
        }
        .thank-you {
            font-size: 1.2em;
            color: #2c5282;
            text-align: center;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e($churchName); ?></h1>
        <p><?php echo e($churchAddress); ?></p>
        <p>Phone: <?php echo e($churchPhone); ?> | Email: <?php echo e($churchEmail); ?></p>
    </div>

    <div class="receipt-number">
        Receipt #: <?php echo e($donation->id); ?>-<?php echo e(date('Ymd', strtotime($donation->donation_date))); ?>

    </div>

    <div class="section">
        <div class="section-title">Donor Information</div>
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span><?php echo e($donation->is_anonymous ? 'Anonymous' : $donation->member->full_name); ?></span>
            </div>
            <?php if(!$donation->is_anonymous): ?>
            <div class="detail-row">
                <span class="detail-label">Member ID:</span>
                <span><?php echo e($donation->member->id); ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span><?php echo e(date('F j, Y', strtotime($donation->donation_date))); ?></span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Donation Details</div>
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <span>$<?php echo e(number_format($donation->amount, 2)); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span><?php echo e(ucfirst(str_replace('_', ' ', $donation->payment_method))); ?></span>
            </div>
            <?php if($donation->campaign): ?>
            <div class="detail-row">
                <span class="detail-label">Campaign:</span>
                <span><?php echo e($donation->campaign->name); ?></span>
            </div>
            <?php endif; ?>
            <?php if($donation->is_recurring): ?>
            <div class="detail-row">
                <span class="detail-label">Recurring:</span>
                <span><?php echo e(ucfirst($donation->recurring_frequency)); ?></span>
            </div>
            <?php endif; ?>
            <?php if($donation->transaction_id): ?>
            <div class="detail-row">
                <span class="detail-label">Transaction ID:</span>
                <span><?php echo e($donation->transaction_id); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="thank-you">
        Thank you for your generous donation!
    </div>

    <div class="footer">
        <p>This receipt is for tax purposes. Please keep it for your records.</p>
        <p><?php echo e($churchName); ?> is a registered non-profit organization.</p>
        <p>Generated on <?php echo e(date('F j, Y')); ?></p>
    </div>
</body>
</html> <?php /**PATH C:\xampp\htdocs\wowmin\resources\views\emails\donations\receipt.blade.php ENDPATH**/ ?>