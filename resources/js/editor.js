
import DOMPurify from 'dompurify';

const el = document.querySelector('#editor');
const hiddenInput = document.querySelector('#content');
const preview = document.querySelector('#preview');

if (el && hiddenInput && preview) {

    const editor = new Editor({
        element: el,
        extensions: [StarterKit],

        content: hiddenInput.value || '',

        onUpdate: ({ editor }) => {
            const html = editor.getHTML();

            hiddenInput.value = html;

            preview.innerHTML = DOMPurify.sanitize(html);
        },
    });

    window.editor = editor;
}


document.getElementById('bold-btn')?.addEventListener('click', () => {
    window.editor?.chain().focus().toggleBold().run();
});