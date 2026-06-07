<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    {{-- YOUR SCRIPT MUST COME AFTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let cropper = null;

            const imageInput = document.getElementById('imageInput');
            const preview = document.getElementById('preview');
            const form = document.getElementById('articleForm');
            const croppedInput = document.getElementById('croppedImage');

            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();

                    reader.onload = function (event) {
                        preview.src = event.target.result;
                        preview.classList.remove('hidden');

                        if (cropper) cropper.destroy();

                        cropper = new Cropper(preview, {
                            aspectRatio: 16 / 9,
                            viewMode: 1,
                            dragMode: 'move',
                        });
                    };

                    reader.readAsDataURL(file);
                });
            }

            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!cropper) return form.submit();

                    const canvas = cropper.getCroppedCanvas({
                        width: 1200,
                        height: 675
                    });

                    canvas.toBlob((blob) => {
                        const reader = new FileReader();

                        reader.onload = function () {
                            croppedInput.value = reader.result;
                            form.submit();
                        };

                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.9);
                });
            }
        });
    </script>