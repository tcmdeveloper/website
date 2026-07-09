import Alpine from 'alpinejs';
import DOMPurify from 'dompurify';
import { marked } from 'marked';

import profileAvatar from './profile-avatar';
import imageUploader from './image-uploader';

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

window.Alpine = Alpine;
window.marked = marked; // IMPORTANT: global sync

Alpine.data('profileAvatar', profileAvatar);
Alpine.data('imageUploader', imageUploader);


// -------------------------
// Shared form handler
// -------------------------
Alpine.data('formHandler', (config = {}) => ({
    firstError: config.firstError || null,
    agreed: config.agreed || false,

    init() {
        this.$nextTick(() => {
            this.focusFirstError();
        });
    },

    focusFirstError() {
        if (!this.firstError) return;
        this.focusField(this.firstError);
    },

    focusField(field) {
        const el = this.$refs[field];
        if (!el) return;

        el.focus();

        if (el.type !== 'password' && typeof el.setSelectionRange === 'function') {
            const len = el.value?.length || 0;
            el.setSelectionRange(len, len);
        }
    }
}));

// -------------------------
// Markdown Editor (Alpine)
// -------------------------
Alpine.data('markdownEditor', () => ({
    previewMarkdown: '',

    init() {
        const textarea = this.$refs.content;

        if (!textarea) {
            console.error('Markdown textarea not found');
            return;
        }

        if (!marked) {
            console.error('marked not loaded');
            return;
        }

        // Configure markdown once
        marked.setOptions({
            gfm: true,
            breaks: true // 👈 THIS is what makes new lines work
        });

        const render = () => {
            const md = textarea.value || '';

            const html = marked.parse(md);   // use direct import, NOT window.marked

            this.previewMarkdown = DOMPurify.sanitize(html);
        };

        // initial render
        render();

        // live update
        textarea.addEventListener('input', render);
    }
}));

// -------------------------
// Start Alpine (CRITICAL)
// -------------------------
Alpine.start();
