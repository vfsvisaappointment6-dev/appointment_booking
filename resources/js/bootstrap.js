import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set Authorization Bearer token from localStorage
const token = localStorage.getItem('auth_token');
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Update Authorization header when token changes
window.addEventListener('storage', function() {
    const updatedToken = localStorage.getItem('auth_token');
    if (updatedToken) {
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${updatedToken}`;
    }
});
