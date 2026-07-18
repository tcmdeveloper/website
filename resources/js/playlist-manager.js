import Sortable from 'sortablejs';

window.playlistManager = function (available, playlist) {
    return {
        availableVideos: available,
        playlistVideos: playlist,

        init() {
            Sortable.create(this.$refs.available, {
                group: 'videos',
                animation: 150,
            });

            Sortable.create(this.$refs.playlist, {
                group: 'videos',
                animation: 150,
            });
        },

        serialize() {
    this.$refs.hiddenInputs.innerHTML = '';

    console.log(this.$refs.playlist.innerHTML);

    const cards = this.$refs.playlist.querySelectorAll('[data-id]');

    cards.forEach((el, index) => {
        console.log(el, el.dataset.id);

        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = `videos[${index}]`;
        input.value = el.dataset.id;

        this.$refs.hiddenInputs.appendChild(input);
    });
}
    };
};