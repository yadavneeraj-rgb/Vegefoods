<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard | Neeraj E-Commerce')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="reverb-key" content="{{ env('REVERB_APP_KEY') }}">
    <meta content="Themesbrand" name="author" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('admin-assets/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('admin-assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />

    <!-- Icons CSS -->
    <link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App CSS -->
    <link href="{{ asset('admin-assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- Add cache busting for CSS -->
    <link href="{{ asset('admin-assets/css/bootstrap.min.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/icons.min.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/app.min.css') }}?v={{ time() }}" rel="stylesheet">

    <script src="{{ asset('admin-assets/js/plugin.js') }}"></script>
</head>

<body data-sidebar="dark">
    @include('admin.layouts.header')

    <div id="layout-wrapper">
        @include('admin.layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.footer')

    <script src="{{ asset('admin-assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin-assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/pages/dashboard-blog.init.js') }}"></script>
    <script src="{{ asset('admin-assets/js/app.js') }}"></script>
    <!-- Your existing scripts -->
       <!-- Your existing scripts -->
    <script src="{{ asset('admin-assets/js/app.js') }}"></script>

    <!-- WebSocket Scripts - BOTH ARE REQUIRED -->
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
        <!-- Your existing scripts -->
    <script src="{{ asset('admin-assets/js/app.js') }}"></script>

    <!-- OPTIMIZED PURE WEBSOCKET Implementation -->
    <script>
        class OrderWebSocket {
            constructor() {
                if (window.orderWebSocketInstance) {
                    console.log('🔄 WebSocket instance already exists, reusing...');
                    return window.orderWebSocketInstance;
                }
                
                this.socket = null;
                this.connected = false;
                this.reconnectAttempts = 0;
                this.maxReconnectAttempts = 5;
                this.connectionToastShown = false;
                this.connect();
                
                window.orderWebSocketInstance = this;
            }

            connect() {
                if (this.socket && this.socket.readyState === WebSocket.OPEN) {
                    console.log('🔗 WebSocket already connected');
                    return;
                }

                try {
                    console.log('🔗 Connecting to Reverb WebSocket...');
                    
                    this.socket = new WebSocket('ws://127.0.0.1:8080/app/c6qhjhxaztbus7abzwqd?protocol=7&client=js&version=8.4.0');
                    
                    this.socket.onopen = () => {
                        console.log('✅ SUCCESS: Connected to Reverb!');
                        this.connected = true;
                        this.reconnectAttempts = 0;
                        
                        // Only show connection toast once per session
                        if (!this.connectionToastShown) {
                            showNotification('Real-time orders connected!', 'success');
                            this.connectionToastShown = true;
                        }
                        
                        this.subscribeToOrders();
                    };
                    
                    this.socket.onmessage = (event) => {
                        this.handleMessage(event);
                    };
                    
                    this.socket.onerror = (error) => {
                        console.error('❌ WebSocket error:', error);
                    };
                    
                    this.socket.onclose = () => {
                        console.log('🔌 WebSocket closed');
                        this.connected = false;
                        this.attemptReconnect();
                    };
                    
                } catch (error) {
                    console.error('❌ Connection error:', error);
                    this.attemptReconnect();
                }
            }

            subscribeToOrders() {
                if (this.socket && this.socket.readyState === WebSocket.OPEN) {
                    const subscribeMessage = {
                        event: 'pusher:subscribe',
                        data: {
                            channel: 'orders'
                        }
                    };
                    this.socket.send(JSON.stringify(subscribeMessage));
                    console.log('✅ Subscribed to orders channel');
                }
            }

            handleMessage(event) {
                try {
                    const data = JSON.parse(event.data);
                    console.log('📨 Received WebSocket message:', data);
                    
                    // Handle subscription success
                    if (data.event === 'pusher_internal:subscription_succeeded') {
                        console.log('✅ Successfully subscribed to orders channel');
                        return;
                    }
                    
                    // Handle connection established
                    if (data.event === 'pusher:connection_established') {
                        console.log('🔗 WebSocket connection established');
                        return;
                    }
                    
                    // Handle your custom order events
                    if (data.event === '.order.created') {
                        console.log('🆕 New order event received:', data);
                        this.handleNewOrder(data);
                    } else if (data.event === '.order.updated') {
                        console.log('📝 Order update event received:', data);
                        this.handleOrderUpdate(data);
                    } else if (data.event === '.order.status.changed') {
                        console.log('🔄 Status change event received:', data);
                        this.handleStatusChange(data);
                    }
                    
                } catch (error) {
                    console.log('📨 Raw WebSocket message:', event.data);
                }
            }

            handleNewOrder(eventData) {
                const order = eventData.order || eventData.data?.order;
                if (order) {
                    console.log('🆕 Processing new order:', order.id);
                    showNotification(`New order #${order.id} from ${order.first_name} ${order.last_name}`, 'success');
                    
                    // Update UI if we're on orders page
                    if (typeof addOrderToUI === 'function') {
                        addOrderToUI(order);
                    }
                }
            }

            handleOrderUpdate(eventData) {
                const order = eventData.order || eventData.data?.order;
                if (order) {
                    console.log('📝 Processing order update:', order.id);
                    showNotification(`Order #${order.id} updated`, 'info');
                    
                    if (typeof updateOrderInUI === 'function') {
                        updateOrderInUI(order);
                    }
                }
            }

            handleStatusChange(eventData) {
                const orderId = eventData.order_id || eventData.data?.order_id;
                const newStatus = eventData.new_status || eventData.data?.new_status;
                
                if (orderId && newStatus) {
                    console.log('🔄 Processing status change:', orderId, newStatus);
                    showNotification(`Order #${orderId} status: ${newStatus}`, 'warning');
                    
                    if (typeof updateOrderStatusInUI === 'function') {
                        updateOrderStatusInUI(orderId, newStatus);
                    }
                }
            }

            attemptReconnect() {
                if (this.reconnectAttempts < this.maxReconnectAttempts) {
                    this.reconnectAttempts++;
                    console.log(`🔄 Reconnecting... (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
                    setTimeout(() => this.connect(), 2000 * this.reconnectAttempts);
                } else {
                    console.error('❌ Max reconnection attempts reached');
                    showNotification('Real-time updates disconnected', 'danger');
                }
            }

            disconnect() {
                if (this.socket) {
                    this.socket.close();
                    this.connected = false;
                    console.log('🔌 WebSocket disconnected manually');
                }
            }
        }

        // Single notification function with prevention of duplicates
        let lastNotificationTime = 0;
        const NOTIFICATION_COOLDOWN = 3000; // 3 seconds
        
        function showNotification(message, type = 'info') {
            const now = Date.now();
            
            // Prevent showing the same notification too frequently
            if (now - lastNotificationTime < NOTIFICATION_COOLDOWN) {
                console.log('⏳ Skipping duplicate notification');
                return;
            }
            
            lastNotificationTime = now;

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

        // Initialize only once when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing WebSocket connection...');
            
            // Check if already initialized
            if (!window.orderWebSocketInstance) {
                window.orderWebSocket = new OrderWebSocket();
            } else {
                console.log('✅ WebSocket already initialized');
            }
        });
    </script>

    @stack('script')
</body>
</html>