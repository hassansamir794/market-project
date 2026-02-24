import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const vitePort = Number(env.VITE_PORT || 5173);

    let appHost = 'localhost';
    try {
        appHost = new URL(env.APP_URL || 'http://localhost').hostname;
    } catch {
        appHost = 'localhost';
    }

    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
        ],
        server: {
            host: '0.0.0.0',
            port: vitePort,
            strictPort: true,
            hmr: {
                host: env.VITE_HMR_HOST || appHost,
                protocol: env.VITE_HMR_PROTOCOL || 'ws',
                port: vitePort,
            },
        },
    };
});
