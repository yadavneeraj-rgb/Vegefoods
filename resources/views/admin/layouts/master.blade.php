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

    <!-- SELF-CONTAINED REVERB WebSocket Implementation -->
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
                this.maxReconnectAttempts = 10;
                this.connectionToastShown = false;
                this.reconnectDelay = 1000;

                // Reverb configuration
                this.reverbConfig = {
                    host: '127.0.0.1',
                    port: '8080',
                    key: 'c6qhjhxaztbus7abzwqd',
                    scheme: 'http'
                };

                this.connect();
                window.orderWebSocketInstance = this;
            }

            connect() {
                if (this.socket) {
                    // Clean up existing connection
                    this.socket.onopen = null;
                    this.socket.onmessage = null;
                    this.socket.onerror = null;
                    this.socket.onclose = null;
                    if (this.socket.readyState === WebSocket.OPEN) {
                        this.socket.close();
                    }
                }

                try {
                    const wsUrl = `${this.reverbConfig.scheme}://${this.reverbConfig.host}:${this.reverbConfig.port}/app/${this.reverbConfig.key}?protocol=7&client=js&version=8.4.0`;
                    console.log('🔗 Connecting to Reverb:', wsUrl);

                    this.socket = new WebSocket(wsUrl);

                    this.socket.onopen = (event) => {
                        console.log('✅ SUCCESS: Connected to Reverb!', event);
                        this.connected = true;
                        this.reconnectAttempts = 0;
                        this.reconnectDelay = 1000;

                        this.updateConnectionStatus('connected', 'Live updates connected');

                        if (!this.connectionToastShown) {
                            this.showNotification('Real-time orders connected!', 'success');
                            this.connectionToastShown = true;
                        }

                        // Small delay before subscribing to ensure connection is ready
                        setTimeout(() => {
                            this.subscribeToOrders();
                        }, 100);
                    };

                    this.socket.onmessage = (event) => {
                        this.handleMessage(event);
                    };

                    this.socket.onerror = (error) => {
                        console.error('❌ WebSocket error:', error);
                        this.updateConnectionStatus('error', 'Connection error');
                    };

                    this.socket.onclose = (event) => {
                        console.log('🔌 WebSocket closed:', event.code, event.reason);
                        this.connected = false;
                        this.updateConnectionStatus('disconnected', `Connection closed: ${event.reason || 'Unknown reason'}`);

                        if (event.code !== 1000) { // Don't reconnect if closed normally
                            this.attemptReconnect();
                        }
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
                } else {
                    console.warn('⚠️ Cannot subscribe - WebSocket not ready');
                }
            }

            // In your master.blade.php - find this handleMessage function:
            handleMessage(event) {
                try {
                    const data = JSON.parse(event.data);
                    console.log('📨 WebSocket Message:', data);

                    switch (data.event) {
                        case 'pusher:connection_established':
                            console.log('🔗 Connection established:', data.data);
                            break;

                        case 'pusher_internal:subscription_succeeded':
                            console.log('✅ Subscribed to channel:', data.channel);
                            this.updateConnectionStatus('connected', `Live - ${data.channel} channel`);
                            break;

                        case 'pusher:ping':
                            // Respond to ping
                            this.socket.send(JSON.stringify({ event: 'pusher:pong', data: {} }));
                            break;

                        // ⚠️ THIS IS THE SECTION TO UPDATE - around lines 175-190:
                        case 'order.created':
                            console.log('🆕 ORDER CREATED EVENT:', data);
                            this.handleNewOrder(data.data || data);
                            break;

                        case 'order.updated':
                            console.log('📝 ORDER UPDATED EVENT:', data);
                            this.handleOrderUpdate(data.data || data);
                            break;

                        case 'order.status.changed':  // ← ADD/UPDATE THIS LINE
                            console.log('🔄 STATUS CHANGED EVENT:', data);
                            this.handleStatusChange(data.data || data);
                            break;

                        default:
                            console.log('📨 Other event:', data.event, data);
                    }

                } catch (error) {
                    console.log('📨 Non-JSON message:', event.data);
                }
            }

            handleNewOrder(eventData) {
                const order = eventData.order;
                if (order && order.id) {
                    console.log('🆕 Processing new order:', order.id);
                    this.showNotification(`New order #${order.id} from ${order.first_name} ${order.last_name}`, 'success');

                    // Call UI update functions if they exist
                    if (typeof window.addOrderToUI === 'function') {
                        window.addOrderToUI(order);
                    }
                }
            }

            handleOrderUpdate(eventData) {
                const order = eventData.order;
                if (order && order.id) {
                    console.log('📝 Processing order update:', order.id);
                    this.showNotification(`Order #${order.id} updated`, 'info');

                    if (typeof window.updateOrderInUI === 'function') {
                        window.updateOrderInUI(order);
                    }
                }
            }

            handleStatusChange(eventData) {
                const orderId = eventData.order_id;
                const newStatus = eventData.new_status;

                if (orderId && newStatus) {
                    console.log('🔄 Processing status change:', orderId, newStatus);
                    this.showNotification(`Order #${orderId} status: ${newStatus}`, 'warning');

                    if (typeof window.updateOrderStatusInUI === 'function') {
                        window.updateOrderStatusInUI(orderId, newStatus);
                    }
                }
            }

            attemptReconnect() {
                if (this.reconnectAttempts < this.maxReconnectAttempts) {
                    this.reconnectAttempts++;
                    const delay = Math.min(this.reconnectDelay * this.reconnectAttempts, 10000);
                    console.log(`🔄 Reconnecting in ${delay}ms... (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);

                    this.updateConnectionStatus('connecting', `Reconnecting... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);

                    setTimeout(() => {
                        console.log('🔄 Attempting reconnect...');
                        this.connect();
                    }, delay);
                } else {
                    console.error('❌ Max reconnection attempts reached');
                    this.showNotification('Real-time updates disconnected', 'danger');
                    this.updateConnectionStatus('error', 'Disconnected - please refresh page');
                }
            }

            updateConnectionStatus(status, message = '') {
                // Update connection status if the element exists
                const statusElement = document.getElementById('connection-status');
                if (statusElement) {
                    const statusMap = {
                        'connected': { text: 'Live', class: 'bg-success' },
                        'error': { text: 'Error', class: 'bg-danger' },
                        'disconnected': { text: 'Offline', class: 'bg-warning' },
                        'connecting': { text: 'Connecting...', class: 'bg-secondary' }
                    };

                    const statusInfo = statusMap[status] || statusMap['connecting'];
                    statusElement.innerHTML = `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
                }

                console.log(`🔌 Connection status: ${status} - ${message}`);
            }

            showNotification(message, type = 'info') {
                // Create a universal notification function
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

                console.log(`📢 ${type.toUpperCase()}: ${message}`);
            }

            disconnect() {
                if (this.socket) {
                    this.socket.close(1000, 'Manual disconnect');
                    this.connected = false;
                    console.log('🔌 WebSocket disconnected manually');
                }
            }
        }

        // Initialize WebSocket when page loads
        document.addEventListener('DOMContentLoaded', function () {
            console.log('🚀 Initializing Reverb WebSocket connection...');

            // Add connection status element if it doesn't exist
            if (!document.getElementById('connection-status')) {
                const statusElement = document.createElement('small');
                statusElement.id = 'connection-status';
                statusElement.className = 'text-muted ms-2';
                statusElement.innerHTML = '<span class="badge bg-secondary">Connecting...</span>';

                // Try to find a good place to insert the status
                const ordersCount = document.querySelector('h3 .badge');
                if (ordersCount) {
                    ordersCount.parentNode.insertBefore(statusElement, ordersCount.nextSibling);
                } else {
                    // Insert at the end of body if no better place found
                    document.body.appendChild(statusElement);
                }
            }

            // Initialize WebSocket
            if (!window.orderWebSocketInstance) {
                window.orderWebSocket = new OrderWebSocket();
            }

            // Make sure Reverb server is running
            console.log('💡 Make sure Reverb server is running: php artisan reverb:start');
        });

        // Export for debugging
        window.debugWebSocket = function () {
            if (window.orderWebSocketInstance) {
                console.log('🔍 WebSocket Debug:', {
                    instance: window.orderWebSocketInstance,
                    connected: window.orderWebSocketInstance.connected,
                    socket: window.orderWebSocketInstance.socket,
                    reconnectAttempts: window.orderWebSocketInstance.reconnectAttempts
                });
            } else {
                console.log('❌ No WebSocket instance found');
            }
        };
    </script>

    @stack('script')
</body>

</html>