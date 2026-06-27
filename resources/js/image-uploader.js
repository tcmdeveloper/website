import Cropper from 'cropperjs';

export default (initialPreview = '') => ({
    cropper: null,
    preview: initialPreview,
    croppedImage: '',

    selectImage(event) {

        const file = event.target.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = e => {

            this.preview = true;

            this.$nextTick(() => {

                this.$refs.preview.src = e.target.result;

                if (this.cropper) {
                    this.cropper.destroy();
                }

                this.cropper = new Cropper(this.$refs.preview, {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });

            });

        };

        reader.readAsDataURL(file);

    },

    submit() {

        if (!this.cropper) {
            this.$root.submit();
            return;
        }

        const canvas = this.cropper.getCroppedCanvas({
            width: 1200,
            height: 675,
        });

        canvas.toBlob(blob => {

            const reader = new FileReader();

            reader.onloadend = () => {

                this.croppedImage = reader.result;

                this.$nextTick(() => {
                    this.$root.submit();
                });

            };

            reader.readAsDataURL(blob);

        }, 'image/jpeg', 0.9);

    }
});