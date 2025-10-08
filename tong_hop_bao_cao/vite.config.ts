import path from 'path';
import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')

  return {
    plugins: [react()],
    base: './', // 🔑 fix lỗi đường ảnh khi build
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'), // ✅ alias chuẩn cho src
      },
    },
    define: {
      'process.env': {
        API_KEY: JSON.stringify(env.GEMINI_API_KEY),
        GEMINI_API_KEY: JSON.stringify(env.GEMINI_API_KEY),
      },
    },
    build: {
      outDir: 'dist',
    },
  }
})