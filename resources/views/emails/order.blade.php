@component('mail::message')
# Order Confirmation

Hi {{ $order->first_name }} {{ $order->last_name }},

Thank you for shopping with us!
Your order **#{{ $order->id }}** has been placed successfully.

@component('mail::panel')
**Total Amount:** ₹{{ $order->amount }}
**Payment Method:** {{ ucfirst($order->payment_method) }}
**Status:** {{ ucfirst($order->payment_status) }}
@endcomponent

### Ordered Items:
@foreach(json_decode($order->cart_items) as $item)
    - **{{ $item->product->name ?? 'Unknown Product' }}** (x{{ $item->quantity ?? 1 }})
@endforeach

### Shipping Address:
{{ $order->street_address }}, {{ $order->town_city }}, {{ $order->state_city }}
Pin: {{ $order->postcode }}
Phone: {{ $order->phone }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent