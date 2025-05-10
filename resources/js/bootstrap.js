import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo'
import Pusher from 'pusher-js';

window.Pusher = Pusher;
// Aktifkan log ke console
// Pusher.logToConsole = true;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 6001,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 6001,
    forceTLS: false,
    encrypted: false,
    enabledTransports: ['ws', 'wss'],
});

// console.log('ENV Vars', {
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT,
// });


// console.log('Mencoba subscribe ke channel pengajuan...');
window.Echo.channel('pengajuan')
    .subscribed(() => {
        console.log('✅ Subscribed to pengajuan');
    })
    .error((err) => {
        console.error('Error in subscription:', err);
    })
    .listenForWhisper('pengajuan-baru', (e) => {
        console.log('Pengajuan Baru diterima:', e);
        Livewire.dispatch('tambahNotif', e);
    });
// console.log('Echo setup selesai');
// window.Echo.connector.pusher.connection.bind('connected', () => {
//     const socketId = window.Echo.socketId();
//     console.log('Socket ID siap:', socketId);
// });

// console.log('Echo instance:', window.Echo);
