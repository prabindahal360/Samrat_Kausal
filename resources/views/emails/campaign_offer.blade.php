<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $campaign->email_subject }}</title>
</head>
<body>
    <p>Hi {{ $customer->first_name ?: 'Customer' }},</p>

    <p>{!! nl2br(e($campaign->email_body)) !!}</p>

    <p>
        <strong>Your Customer Category:</strong> {{ $customer->customer_category }}<br>
        <strong>Your Discount:</strong> {{ $campaign->discount_percentage }}% off
    </p>

    @if(!empty($customer->recommended_products))
        <p><strong>Recommended Products from our catalog:</strong></p>
        <ul>
            @foreach($customer->recommended_products as $product)
                <li>
                    <strong>{{ $product['name'] ?? '-' }}</strong>
                    @if(!empty($product['category']))
                        — {{ $product['category'] }}
                    @endif
                    @if(isset($product['unit_price']))
                        — ${{ number_format($product['unit_price'], 2) }}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <p>Best regards,<br>Your Store Team</p>
</body>
</html>