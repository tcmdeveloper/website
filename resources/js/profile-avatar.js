import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export default function profileAvatar() {
    return {
        cropper: null,
        cropperOpen: false,
        imageSrc: null,

        openFilePicker() {
            console.log('OPEN FILE PICKER');
            this.$refs.avatar.click();
        },

        handleAvatarSelected(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.cropperOpen = true;

                this.$nextTick(() => {
                    this.$nextTick(() => {
                        const image = this.$refs.cropperImage;

                        this.cropper = new Cropper(image, {
    aspectRatio: 1,
    viewMode: 1,
    dragMode: 'move',
    autoCropArea: 1,
});
                    });
                });
            };

            reader.readAsDataURL(file);
        },

        closeCropper() {
            this.cropperOpen = false;

            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        saveCrop() {
            console.log('SAVE CLICKED');
            console.log('cropper:', this.cropper);

            if (!this.cropper) {
                console.error('Cropper not initialized');
                return;
            } 

            const canvas = this.cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            });

            canvas.toBlob((blob) => {
                const formData = new FormData();
                formData.append('avatar', blob, 'avatar.jpg');

                fetch('/admin/profile/avatar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: formData
                }).then(() => window.location.reload());
            }, 'image/jpeg', 0.9);
        }
    };
}