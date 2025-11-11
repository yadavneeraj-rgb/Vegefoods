@extends('admin.layouts.master')
@section('title', 'Orders | Neeraj - Ecommerce')

@section('content')

    <div class="container mt-4">
        <h3 class="mb-3">All Orders</h3>

        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>User</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td>{{ $order->razorpay_order_id }}</td>
                                <td>₹{{ number_format($order->amount, 2) }}</td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                <td>
                                    @if($order->payment_status == 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif($order->payment_status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">No orders found yet.</div>
        @endif
    </div>

@endsection