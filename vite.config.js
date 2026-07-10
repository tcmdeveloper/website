import { defineConfig } from 'vite';

// Laravel ↔ Vite integration.
// Handles Blade asset loading, HMR and manifest generation.
import laravel from 'laravel-vite-plugin';

// Tailwind CSS v4 Vite plugin.
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({

    // --------------------------------------------------
    // Build options
    // --------------------------------------------------

    build: {

        // Generate source maps for production builds.
        //
        // Useful because browser errors will point to your
        // original source files instead of the minified bundle.
        //
        // Example:
        // resources/js/app.js:12
        //
        // instead of:
        // app-D3f89a.js:1:8542

        sourcemap: process.env.NODE_ENV !== 'production',

        // Custom Rollup configuration.
        // Rollup is the bundler that Vite uses internally.
        rollupOptions: {

            output: {

                // Control where generated assets are stored.
                //
                // By default, Vite places everything inside:
                //
                // public/build/assets/
                //
                // This function moves font files into:
                //
                // public/build/assets/fonts/
                assetFileNames: (assetInfo) => {

                    // Get the original asset filename.
                    //
                    // Examples:
                    // "open-sans-latin-400-normal.woff2"
                    // "app.css"
                    const name = assetInfo.names?.[0] ?? '';

                    // Match font files.
                    if (/\.(woff2?|ttf|otf|eot)$/.test(name)) {

                        // Output fonts here:
                        //
                        // public/build/assets/fonts/
                        //
                        // [name]     → original filename
                        // [hash]     → cache-busting hash
                        // [extname]  → original extension
                        return 'assets/fonts/[name]-[hash][extname]';
                    }

                    // Everything else goes into:
                    //
                    // public/build/assets/
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },

    
    // --------------------------------------------------
    // Vite plugins
    // --------------------------------------------------

    plugins: [

        laravel({

            // Entry points.
            //
            // Vite starts from these files and follows all imports.
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/timeline.js',
                'resources/js/editor.js',
            ],

            // Automatically refresh the browser when:
            //
            // - Blade files change
            // - Routes change
            // - Lang files change
            refresh: true,
        }),

        // Enables Tailwind CSS v4 processing.
        tailwindcss(),
    ],


    // --------------------------------------------------
    // Development server (ignored in production builds)
    // --------------------------------------------------

    server: {

        // Allow external devices on your network
        // to access Vite.
        //
        // Example:
        //
        // http://192.168.x.x:5173
        host: true,

        hmr: {

            // Hot Module Reload host.
            //
            // Should match your Laravel Herd / Valet domain.
            host: 'metrix-v1.test',
        },

        watch: {

            // Use polling instead of filesystem events.
            //
            // Useful for:
            //
            // - Docker
            // - WSL
            // - network drives
            // - virtual machines
            usePolling: true,

            // Check for file changes every 100ms.
            interval: 100,
        },
    },
});