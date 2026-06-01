{{-- resources/views/admin/dashboard.blade.php --}}

<x-layouts.app 
    title="Transcriptions"
    subtitle="Here are all the transcriptions you've been working on."
>

    {{-- Admin buttons --}}

    <x-ui.card class="mx-auto mb-8 px-4 py-4">
        
        <div class="flex flex-wrap justify-between gap-3 mb-1">

            <x-ui.button href="{{route('transcriptions.create')}}" variant="success">
                Create new transcript
            </x-ui.button>

            <x-ui.button href="{{route('transcriptions.index')}}" variant="secondary">
                Back to dashboard
            </x-ui.button>

        </div>
    
    </x-ui.card>


    <x-ui.card class="mx-auto">

        <div class="overflow-x-auto bg-white rounded-xl shadow">

            <table class="min-w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Language</th>
                        <th class="px-6 py-3">Duration</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Created</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse ($transcriptions as $transcription)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 text-gray-500">
                                #{{ $transcription->id }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $transcription->title ?? 'Untitled' }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ strtoupper($transcription->language ?? 'auto') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $transcription->duration ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($transcription->status === 'completed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        Completed
                                    </span>
                                @elseif($transcription->status === 'processing')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                        Processing
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        Failed
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $transcription->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">

                                {{-- <a href="{{ route('transcriptions.show', $transcription) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium">
                                    View
                                </a>

                                <a href="{{ route('transcriptions.download', $transcription) }}"
                                class="text-green-600 hover:text-green-800 font-medium">
                                    Download
                                </a>

                                <form action="{{ route('transcriptions.destroy', $transcription) }}"
                                    method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Delete this transcription?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600 hover:text-red-800 font-medium">
                                        Delete
                                    </button>
                                </form> --}}

                            </td>

                        </tr>
                        
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No transcriptions found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>


        </div>


    </x-ui.card>


</x-layouts.app>
