import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const isHttps = (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https';

const echoConfig = {
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: isHttps,
    scheme: isHttps ? 'wss' : 'ws',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth',
};

console.log('🔧 Echo Configuration:', echoConfig);

window.Echo = new Echo(echoConfig);

// Listen for connection events
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Reverb connected successfully');
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('❌ Reverb connection error:', error);
});

window.Echo.connector.pusher.connection.bind('failed', (error) => {
    console.error('❌ Reverb connection failed:', error);
});
