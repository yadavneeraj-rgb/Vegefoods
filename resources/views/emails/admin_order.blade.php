@component('mail::message')
# 🛒 New Order Received

Hello Admin,

A new order has been placed on your e-commerce store.

@component('mail::panel')
**Order ID:** #{{ $order->id }}
**Customer Name:** {{ $order->first_name }} {{ $order->last_name }}
**Total Amount:** ₹{{ $order->amount }}
**Payment Method:** {{ ucfirst($order->payment_method) }}
**Status:** {{ ucfirst($order->payment_status) }}
@endcomponent

### Shipping Address:
{{ $order->street_address }}, {{ $order->town_city }}, {{ $order->state_city }}
Pin: {{ $order->postcode }}
Phone: {{ $order->phone }}

### Ordered Items:
@foreach(json_decode($order->cart_items) as $item)
    - {{ $item->product->name ?? 'Unknown Product' }} (x{{ $item->quantity ?? 1 }})
@endforeach

@component('mail::button', ['url' => url('/admin/orders/' . $order->id)])
View Order in Admin Panel
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent