import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [react()],
    build: {
        lib: {
            entry: 'src/main.jsx',
            name: 'DataChat',
            fileName: 'datachat-widget',
            formats: ['umd']
        },
        rollupOptions: {
            external: [],
            output: {
                globals: {},
                assetFileNames: 'datachat-widget.[ext]'
            }
        },
        outDir: '../../../public',
        emptyOutDir: false
    }
})