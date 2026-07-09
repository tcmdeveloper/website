import Alpine from 'alpinejs';
import DOMPurify from 'dompurify';
import { marked } from 'marked';



document.addEventListener('alpine:init', () => {
    Alpine.data('markdownEditor', () => ({
        previewMarkdown: '',

        init() {
            const updatePreview = () => {
                this.previewMarkdown = DOMPurify.sanitize(
                    marked.parse(this.$refs.content.value || '')
                );
            };

            updatePreview();

            this.$refs.content.addEventListener('input', updatePreview);
        }
    }));
});

window.Alpine = Alpine;

Alpine.start();