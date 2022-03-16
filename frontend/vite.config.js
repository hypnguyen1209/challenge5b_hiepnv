import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { resolve } from 'path'
// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    server: {
        proxy: {
            '/api': {
                target: 'http://aws.hypnguyen.com:10000/api',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, '')
            },
            '/uploads': {
                target: 'http://aws.hypnguyen.com:10000/uploads',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, '')
            }
        }
    },
    resolve: {
        extensions: [".js", ".vue", ".json"],
        alias: {
            '@': path.resolve(__dirname, 'src'),
        }
    },
    build: {
        sourcemap: false,
        rollupOptions: {
            input: {
                main: resolve(__dirname, 'index.html'),
            }
        }
    }
})
