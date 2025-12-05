// public/assets/js/main.js - Main JavaScript functions

// Global configuration
const CONFIG = {
    BASE_URL: '/pool-snack-system/public',
    API_URL: '/pool-snack-system/public/api',
    SESSION_TIMEOUT: 7200000 // 2 hours in milliseconds
};

// Utility functions
class PoolSnackUtils {
    static formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    static formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    static showLoading(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        button.disabled = true;
        return originalText;
    }

    static hideLoading(button, originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }

    static showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast show align-items-center text-bg-${type} border-0`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    static validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    static validateFile(file, allowedTypes, maxSize) {
        if (!allowedTypes.includes(file.type)) {
            return { valid: false, message: 'Format file tidak didukung' };
        }
        
        if (file.size > maxSize) {
            return { valid: false, message: 'Ukuran file terlalu besar' };
        }
        
        return { valid: true };
    }
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Cart management
class CartManager {
    constructor() {
        this.init();
    }

    init() {
        this.updateCartCount();
        this.setupEventListeners();
    }

    async updateCartCount() {
        try {
            const response = await fetch(`${CONFIG.API_URL}/cart/count`);
            const data = await response.json();
            
            if (data.success) {
                document.querySelectorAll('.cart-count').forEach(element => {
                    element.textContent = data.count;
                });
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    setupEventListeners() {
        // Global cart event listeners
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart') || 
                e.target.closest('.add-to-cart')) {
                this.addToCart(e);
            }
        });
    }

    async addToCart(event) {
        const button = event.target.classList.contains('add-to-cart') ? 
            event.target : event.target.closest('.add-to-cart');
        
        const menuId = button.dataset.menuId;
        const menuName = button.dataset.menuName;
        
        const originalText = PoolSnackUtils.showLoading(button);
        
        try {
            const response = await fetch(`${CONFIG.API_URL}/cart/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    quantity: 1
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                PoolSnackUtils.showToast(`${menuName} berhasil ditambahkan ke keranjang`, 'success');
                this.updateCartCount();
            } else {
                PoolSnackUtils.showToast(data.message || 'Gagal menambahkan ke keranjang', 'danger');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            PoolSnackUtils.showToast('Terjadi kesalahan saat menambahkan ke keranjang', 'danger');
        } finally {
            PoolSnackUtils.hideLoading(button, originalText);
        }
    }
}

// Notification system
class NotificationSystem {
    constructor() {
        this.notificationCount = 0;
        this.init();
    }

    init() {
        this.startPolling();
    }

    async checkNotifications() {
        try {
            const response = await fetch(`${CONFIG.API_URL}/notifications`);
            const data = await response.json();
            
            if (data.success && data.notifications.length > 0) {
                this.showNotifications(data.notifications);
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }

    showNotifications(notifications) {
        notifications.forEach(notification => {
            PoolSnackUtils.showToast(notification.message, notification.type);
        });
    }

    startPolling() {
        // Check for new notifications every 30 seconds
        setInterval(() => {
            this.checkNotifications();
        }, 30000);
    }
}

// Session management
class SessionManager {
    constructor() {
        this.checkSession();
        this.setupAutoLogout();
    }

    checkSession() {
        const lastActivity = localStorage.getItem('lastActivity');
        const now = Date.now();
        
        if (lastActivity && (now - lastActivity > CONFIG.SESSION_TIMEOUT)) {
            this.logout();
        }
        
        this.updateLastActivity();
    }

    updateLastActivity() {
        localStorage.setItem('lastActivity', Date.now());
    }

    setupAutoLogout() {
        // Update activity on user interaction
        ['click', 'keypress', 'scroll', 'mousemove'].forEach(event => {
            document.addEventListener(event, () => {
                this.updateLastActivity();
            });
        });

        // Check session every minute
        setInterval(() => {
            this.checkSession();
        }, 60000);
    }

    logout() {
        localStorage.removeItem('lastActivity');
        window.location.href = `${CONFIG.BASE_URL}/logout`;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize managers
    if (document.querySelector('.add-to-cart')) {
        new CartManager();
    }
    
    if (document.querySelector('[data-role="kasir"]') || document.querySelector('[data-role="customer"]')) {
        new NotificationSystem();
    }
    
    new SessionManager();

    // Add global error handler
    window.addEventListener('error', function(e) {
        console.error('Global error:', e.error);
        PoolSnackUtils.showToast('Terjadi kesalahan tak terduga', 'danger');
    });

    // Add global AJAX error handler
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        PoolSnackUtils.showToast('Terjadi kesalahan jaringan', 'danger');
    });
});

// Export for global access
window.PoolSnackUtils = PoolSnackUtils;
window.CartManager = CartManager;