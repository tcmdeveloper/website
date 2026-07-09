import Alpine from 'alpinejs';


window.Alpine = Alpine;


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
// Start Alpine (CRITICAL)
// -------------------------
Alpine.start();
