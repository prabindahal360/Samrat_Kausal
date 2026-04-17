<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $personalizedSubject }}</title>
</head>
<body>
    <p>Hi {{ $customer->first_name ?: 'Customer' }},</p>

    <div>
        {!! nl2br(e($personalizedBody)) !!}
    </div>

    <p>
        <strong>Your Customer Category:</strong> {{ $customer->customer_category ?: '-' }}<br>
        <strong>Your Discount:</strong> {{ $campaign->discount_percentage }}% off
    </p>

    @if(!empty($recommendedProducts))
        <p><strong>Recommended Products from our catalog:</strong></p>
        <ul>
            @foreach($recommendedProducts as $product)
                <li>
                    <strong>{{ $product['name'] ?? '-' }}</strong>
                    @if(!empty($product['category']))
                        — {{ $product['category'] }}
                    @endif
                    @if(isset($product['unit_price']))
                        — ${{ number_format((float) $product['unit_price'], 2) }}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <p>Best regards,<br>Your Store Team</p>
</body>
</html>