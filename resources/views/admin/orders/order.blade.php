@extends('admin.layouts.master')
@section('title', 'Orders | Neeraj - Ecommerce')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">All Orders 
            <span id="orders-count" class="badge bg-primary">{{ $orders->count() }}</span>
            <small class="text-muted" id="connection-status">
                <span class="badge bg-secondary">Connecting...</span>
            </small>
        </h3>

        <!-- Toast Container -->
        <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>

        <div id="orders-container">
            @if($orders->count() > 0)
                <div class="accordion" id="ordersAccordion">
                    @foreach ($orders as $order)
                        <div class="accordion-item" id="order-{{ $order->id }}" data-order-id="{{ $order->id }}">
                            <h2 class="accordion-header" id="heading{{ $order->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $order->id }}">
                                    <div class="d-flex justify-content-between w-100 me-3">
                                        <div>
                                            <strong>Order #{{ $order->id }}</strong> -
                                            {{ $order->user->name ?? 'Guest' }} -
                                            ₹{{ number_format($order->amount, 2) }}
                                        </div>
                                        <div>
                                            <span class="badge order-status-badge 
                                                @if($order->payment_status == 'success') bg-success
                                                @elseif($order->payment_status == 'failed') bg-danger
                                                @else bg-warning text-dark @endif">
                                                @if($order->payment_status == 'success') Success
                                                @elseif($order->payment_status == 'failed') Failed
                                                @else Pending @endif
                                            </span>
                                            <small class="text-muted ms-2">{{ $order->created_at->format('M d, h:i A') }}</small>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $order->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Customer Information</h6>
                                            <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                                            <p><strong>Email:</strong> {{ $order->email }}</p>
                                            <p><strong>Phone:</strong> {{ $order->phone }}</p>
                                            <p><strong>Address:</strong> {{ $order->street_address }},
                                                {{ $order->apartment_suite ? $order->apartment_suite . ', ' : '' }}
                                                {{ $order->town_city }}, {{ $order->state_city }} - {{ $order->postcode }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Order Details</h6>
                                            <p><strong>Order ID:</strong> {{ $order->razorpay_order_id }}</p>
                                            <p><strong>Payment ID:</strong> {{ $order->razorpay_payment_id ?? 'N/A' }}</p>
                                            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                            <p><strong>Status:</strong> 
                                                <span class="badge order-detail-status 
                                                    @if($order->payment_status == 'success') bg-success
                                                    @elseif($order->payment_status == 'failed') bg-danger
                                                    @else bg-warning text-dark @endif">
                                                    @if($order->payment_status == 'success') Success
                                                    @elseif($order->payment_status == 'failed') Failed
                                                    @else Pending @endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">No orders found yet.</div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Order page loaded - WebSocket handlers ready');
    
    // Update orders count
    function updateOrdersCount() {
        const ordersCount = document.querySelectorAll('.accordion-item').length;
        document.getElementById('orders-count').textContent = ordersCount;
    }
    
    // Update connection status
    function updateConnectionStatus(status, message = '') {
        const statusElement = document.getElementById('connection-status');
        if (!statusElement) return;
        
        const statusMap = {
            'connected': { text: 'Live', class: 'bg-success' },
            'error': { text: 'Error', class: 'bg-danger' },
            'disconnected': { text: 'Offline', class: 'bg-warning' },
            'connecting': { text: 'Connecting...', class: 'bg-secondary' }
        };
        
        const statusInfo = statusMap[status] || statusMap['connecting'];
        statusElement.innerHTML = `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
        
        if (message) {
            console.log(`Connection status: ${status} - ${message}`);
        }
    }
    
    // Function to add new order to UI
    window.addOrderToUI = function(order) {
        console.log('🆕 Adding new order to UI:', order.id);
        
        const ordersContainer = document.getElementById('orders-container');
        const noOrdersAlert = ordersContainer.querySelector('.alert-info');
        
        // Remove "no orders" message if it exists
        if (noOrdersAlert) {
            noOrdersAlert.remove();
        }
        
        // Create new order HTML
        const newOrderHtml = `
            <div class="accordion-item" id="order-${order.id}" data-order-id="${order.id}">
                <h2 class="accordion-header" id="heading${order.id}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse${order.id}" aria-expanded="false"
                        aria-controls="collapse${order.id}">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <div>
                                <strong>Order #${order.id}</strong> -
                                ${order.first_name} ${order.last_name} -
                                ₹${parseFloat(order.amount).toFixed(2)}
                            </div>
                            <div>
                                <span class="badge order-status-badge ${getStatusBadgeClass(order.payment_status)}">
                                    ${getStatusText(order.payment_status)}
                                </span>
                                <small class="text-muted ms-2">Just now</small>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse${order.id}" class="accordion-collapse collapse"
                    aria-labelledby="heading${order.id}" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body">
                        <div class="alert alert-info">
                            <small>This order was received in real-time. Full details will be available shortly.</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Get or create orders accordion
        let ordersAccordion = document.getElementById('ordersAccordion');
        if (!ordersAccordion) {
            ordersAccordion = document.createElement('div');
            ordersAccordion.id = 'ordersAccordion';
            ordersAccordion.className = 'accordion';
            ordersContainer.appendChild(ordersAccordion);
        }
        
        // Add new order to the top
        ordersAccordion.insertAdjacentHTML('afterbegin', newOrderHtml);
        
        // Highlight the new order
        const newOrderElement = document.getElementById(`order-${order.id}`);
        newOrderElement.style.backgroundColor = '#d4edda';
        setTimeout(() => {
            newOrderElement.style.backgroundColor = '';
        }, 3000);
        
        // Update orders count
        updateOrdersCount();
        
        showNotification(`New order #${order.id} from ${order.first_name} ${order.last_name}`, 'success');
    };
    
    // Function to update existing order
    window.updateOrderInUI = function(order) {
        console.log('📝 Updating order in UI:', order.id);
        
        const orderElement = document.getElementById(`order-${order.id}`);
        if (orderElement) {
            // Update the order amount in the header
            const amountElement = orderElement.querySelector('.accordion-button strong');
            if (amountElement && amountElement.textContent.includes('₹')) {
                const currentText = amountElement.textContent;
                const newText = currentText.replace(/₹[\d,]+\.\d{2}/, `₹${parseFloat(order.amount).toFixed(2)}`);
                amountElement.textContent = newText;
            }
            
            // Highlight the updated order
            orderElement.style.backgroundColor = '#fff3cd';
            setTimeout(() => {
                orderElement.style.backgroundColor = '';
            }, 3000);
            
            showNotification(`Order #${order.id} updated`, 'info');
        }
    };
    
    // Function to update order status
    window.updateOrderStatusInUI = function(orderId, newStatus) {
        console.log('🔄 Updating order status:', orderId, newStatus);
        
        const orderElement = document.getElementById(`order-${orderId}`);
        if (orderElement) {
            const badges = orderElement.querySelectorAll('.order-status-badge, .order-detail-status');
            badges.forEach(badge => {
                badge.className = `badge ${getStatusBadgeClass(newStatus)}`;
                badge.textContent = getStatusText(newStatus);
            });
            
            // Highlight the status change
            orderElement.style.backgroundColor = '#ffeaa7';
            setTimeout(() => {
                orderElement.style.backgroundColor = '';
            }, 3000);
            
            showNotification(`Order #${orderId} status: ${newStatus}`, 'warning');
        }
    };
    
    // Helper functions
    function getStatusBadgeClass(status) {
        const statusClasses = {
            'success': 'bg-success',
            'failed': 'bg-danger',
            'pending': 'bg-warning text-dark'
        };
        return statusClasses[status] || 'bg-secondary';
    }
    
    function getStatusText(status) {
        const statusTexts = {
            'success': 'Success',
            'failed': 'Failed',
            'pending': 'Pending'
        };
        return statusTexts[status] || status;
    }
    
    function showNotification(message, type = 'info') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }
    
    // Initial setup
    updateOrdersCount();
    updateConnectionStatus('connecting');
    
    console.log('✅ Order page WebSocket handlers ready');
});
</script>
@endsection