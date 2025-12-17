import './bootstrap';

import Alpine from 'alpinejs';
// import Echo from 'laravel-echo';
window.Alpine = Alpine;
// window.Pusher = require('pusher-js');
Alpine.start();

// Uncomment when laravel-echo and pusher-js are installed
// npm install laravel-echo pusher-js

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
