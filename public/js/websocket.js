// public/js/websocket.js
class OrderWebSocket {
    constructor() {
        this.echo = null;
        this.connected = false;
        console.log("🚀 Starting Laravel Reverb WebSocket...");
        this.initialize();
    }

    initialize() {
        // Check if required libraries are loaded
        if (typeof Echo === 'undefined') {
            console.error('❌ Echo is not defined.');
            return;
        }

        if (typeof Pusher === 'undefined') {
            console.error('❌ Pusher is not defined. Required for Reverb.');
            return;
        }

        try {
            // Initialize Echo with Reverb
            this.echo = new Echo({
                broadcaster: 'reverb',
                key: 'c6qhjhxaztbus7abzwqd',
                wsHost: '127.0.0.1',
                wsPort: 8080,
                wssPort: 443,
                forceTLS: false,
                enabledTransports: ['ws', 'wss'],
            });

            console.log("✅ Echo instance created");

            // Set up connection monitoring
            this.setupConnectionMonitoring();

        } catch (error) {
            console.error("❌ Failed to initialize WebSocket:", error);
        }
    }

    setupConnectionMonitoring() {
        // Wait a moment for connection to establish
        setTimeout(() => {
            this.checkConnection();
        }, 1000);

        // Continue checking every 2 seconds
        const checkInterval = setInterval(() => {
            if (this.connected) {
                clearInterval(checkInterval);
                return;
            }
            this.checkConnection();
        }, 2000);

        // Timeout after 10 seconds
        setTimeout(() => {
            if (!this.connected) {
                clearInterval(checkInterval);
                console.error("❌ WebSocket connection timeout");
                this.showNotification('WebSocket connection failed', 'danger');
            }
        }, 10000);
    }

    checkConnection() {
        try {
            if (this.echo && this.echo.connector && this.echo.connector.socket) {
                const socket = this.echo.connector.socket;
                
                if (socket.readyState === WebSocket.OPEN) {
                    if (!this.connected) {
                        console.log("✅ WebSocket connected successfully!");
                        this.connected = true;
                        this.initializeOrderListeners();
                        this.showNotification('Real-time updates connected!', 'success');
                    }
                } else {
                    const states = {
                        0: 'CONNECTING',
                        1: 'OPEN', 
                        2: 'CLOSING',
                        3: 'CLOSED'
                    };
                    console.log(`⏳ WebSocket state: ${states[socket.readyState]}`);
                }
            } else {
                console.log("⏳ Waiting for WebSocket initialization...");
            }
        } catch (error) {
            console.log("⏳ WebSocket not ready yet...");
        }
    }

    initializeOrderListeners() {
        if (!this.connected) {
            console.error("Cannot initialize listeners: WebSocket not connected");
            return;
        }

        try {
            console.log("🎧 Setting up order event listeners...");

            this.echo.channel("orders")
                .listen(".order.created", (event) => {
                    console.log("🆕 New order received:", event);
                    this.handleNewOrder(event);
                })
                .listen(".order.updated", (event) => {
                    console.log("📝 Order updated:", event);
                    this.handleOrderUpdate(event);
                })
                .listen(".order.status.changed", (event) => {
                    console.log("🔄 Order status changed:", event);
                    this.handleStatusChange(event);
                });

            console.log("✅ Successfully listening to order updates");

        } catch (error) {
            console.error("❌ Error setting up order listeners:", error);
        }
    }

    handleNewOrder(event) {
        this.showNotification(`New order #${event.order.id} received`, "success");
        
        if (typeof window.handleNewOrder === 'function') {
            window.handleNewOrder(event);
        }
    }

    handleOrderUpdate(event) {
        this.showNotification(`Order #${event.order.id} updated`, "info");
        
        if (typeof window.handleOrderUpdate === 'function') {
            window.handleOrderUpdate(event);
        }
    }

    handleStatusChange(event) {
        this.showNotification(`Order #${event.order_id} status: ${event.new_status}`, "warning");
        
        if (typeof window.handleOrderStatusChange === 'function') {
            window.handleOrderStatusChange(event);
        }
    }

    showNotification(message, type = "info") {
        console.log(`[${type.toUpperCase()}] ${message}`);

        if (typeof bootstrap !== "undefined") {
            let toastContainer = document.getElementById("toast-container");
            if (!toastContainer) {
                toastContainer = document.createElement("div");
                toastContainer.id = "toast-container";
                toastContainer.className = "toast-container position-fixed top-0 end-0 p-3";
                toastContainer.style.zIndex = "9999";
                document.body.appendChild(toastContainer);
            }

            const toastId = "toast-" + Date.now();
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

            toastContainer.insertAdjacentHTML("beforeend", toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            toastElement.addEventListener("hidden.bs.toast", () => {
                toastElement.remove();
            });
        }
    }

    disconnect() {
        if (this.echo) {
            this.echo.disconnect();
            this.connected = false;
            console.log("WebSocket disconnected");
        }
    }
}

// Initialize when page loads
document.addEventListener("DOMContentLoaded", function () {
    console.log("🚀 Initializing Order WebSocket...");
    window.orderWebSocket = new OrderWebSocket();
});