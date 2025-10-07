import './bootstrap';
import 'jquery';

// Global error handler
window.showError = function(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message,
            confirmButtonColor: '#667eea'
        });
    } else {
        alert('Error: ' + message);
    }
};

// Global success handler
window.showSuccess = function(message, callback = null) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            confirmButtonColor: '#667eea'
        }).then((result) => {
            if (callback && typeof callback === 'function') {
                callback();
            }
        });
    } else {
        alert('Success: ' + message);
        if (callback && typeof callback === 'function') {
            callback();
        }
    }
};

// Global loading handler
window.showLoading = function() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
};

// Close loading
window.hideLoading = function() {
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
};
