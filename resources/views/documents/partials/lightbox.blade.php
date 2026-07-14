<div
    x-cloak
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/95"

    @click="close()"

    @touchstart="touchStart($event)"
    @touchend="touchEnd($event)"
>

    {{-- Previous --}}
    <button
        x-show="current > 0"
        @click.stop="prev()"
        class="absolute left-4 sm:left-6 text-white text-5xl sm:text-6xl hover:text-gray-300 select-none"
    >
        ‹
    </button>

    {{-- Image --}}
    <img
        :src="images[current]"
        class="max-w-[95vw] max-h-[95vh] object-contain"
        @click.stop
    >

    {{-- Next --}}
    <button
        x-show="current < images.length - 1"
        @click.stop="next()"
        class="absolute right-4 sm:right-6 text-white text-5xl sm:text-6xl hover:text-gray-300 select-none"
    >
        ›
    </button>

    {{-- Close --}}
    <button
        @click="close()"
        class="absolute top-4 right-4 sm:top-6 sm:right-6 text-white text-4xl sm:text-5xl hover:text-gray-300"
    >
        ✕
    </button>

    {{-- Page Counter --}}
    <div
        class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded bg-black/60 px-4 py-2 text-sm text-white"
    >
        <span x-text="current + 1"></span>
        /
        <span x-text="images.length"></span>
    </div>

</div>