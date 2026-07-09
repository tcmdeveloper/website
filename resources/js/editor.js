import DOMPurify from 'dompurify';
import { marked } from 'marked';

document.addEventListener('alpine:init', () => {
    Alpine.data('markdownEditor', () => ({
        previewMarkdown: '',

        init() {
            const textarea = this.$refs.content;

            marked.setOptions({
                gfm: true,
                breaks: true,
            });

            const render = () => {
                this.previewMarkdown = DOMPurify.sanitize(
                    marked.parse(textarea.value || '')
                );
            };

            render();

            textarea.addEventListener('input', render);
        }
    }));
});