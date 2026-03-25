<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Special Offer</title>
</head>
<body>
    <p>Hi {{ $customer->first_name ?: 'Customer' }},</p>

    <p>
        Based on your shopping history, we selected some products you may like.
    </p>

    <p>
        <strong>Your Customer Category:</strong> {{ $customer->customer_category }}<br>
        <strong>Your Special Discount:</strong> {{ $customer->discount_percentage }}% off
    </p>

    @if(!empty($customer->recommended_products))
        <p><strong>Recommended Products:</strong></p>
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

    <p>
        Use your discount on your next purchase and enjoy products selected just for you.
    </p>

    <p>Best regards,<br>Your Store Team</p>
</body>
</html>