import './bootstrap';

import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
window.Alpine = Alpine;
window.Pusher = require('pusher-js');
Alpine.start();




window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
