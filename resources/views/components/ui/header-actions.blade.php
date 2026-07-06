{{-- resources/views/componenets/ui/header-actions.blade.php --}}

@props([
    'title' => null,
    'subtitle' => null,
    'actions' => [],

])

<div class="flex items-center justify-between mb-6 space-x-2">


    <div class="grow">
        <h1 class="text-2xl font-semibold">
            {{$title}}
        </h1>

        <p class="text-sm text-zinc-500 mt-1">
            {{$subtitle}}
        </p>
    </div>


    @if($actions)
        
        @foreach($actions as $action)

            <x-ui.button
                href="{{ $action['href'] }}"
                size="sm"
                variant="{{ 
                    $action['variant'] ?? 'primary'
                }}"
            >   

                {{-- Show icon if set in actions --}}
                @if(isset($action['icon']))
                    <x-dynamic-component
                        :component="$action['icon']"
                        class="w-3 h-3"
                    />
                @endif


                {{ $action['label'] }}
            </x-ui.button>
        @endforeach

    @endif


</div>