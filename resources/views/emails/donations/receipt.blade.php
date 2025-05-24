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
        <h1>{{ $churchName }}</h1>
        <p>{{ $churchAddress }}</p>
        <p>Phone: {{ $churchPhone }} | Email: {{ $churchEmail }}</p>
    </div>

    <div class="receipt-number">
        Receipt #: {{ $donation->id }}-{{ date('Ymd', strtotime($donation->donation_date)) }}
    </div>

    <div class="section">
        <div class="section-title">Donor Information</div>
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span>{{ $donation->is_anonymous ? 'Anonymous' : $donation->member->full_name }}</span>
            </div>
            @if(!$donation->is_anonymous)
            <div class="detail-row">
                <span class="detail-label">Member ID:</span>
                <span>{{ $donation->member->id }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span>{{ date('F j, Y', strtotime($donation->donation_date)) }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Donation Details</div>
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <span>${{ number_format($donation->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span>{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</span>
            </div>
            @if($donation->campaign)
            <div class="detail-row">
                <span class="detail-label">Campaign:</span>
                <span>{{ $donation->campaign->name }}</span>
            </div>
            @endif
            @if($donation->is_recurring)
            <div class="detail-row">
                <span class="detail-label">Recurring:</span>
                <span>{{ ucfirst($donation->recurring_frequency) }}</span>
            </div>
            @endif
            @if($donation->transaction_id)
            <div class="detail-row">
                <span class="detail-label">Transaction ID:</span>
                <span>{{ $donation->transaction_id }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="thank-you">
        Thank you for your generous donation!
    </div>

    <div class="footer">
        <p>This receipt is for tax purposes. Please keep it for your records.</p>
        <p>{{ $churchName }} is a registered non-profit organization.</p>
        <p>Generated on {{ date('F j, Y') }}</p>
    </div>
</body>
</html> 