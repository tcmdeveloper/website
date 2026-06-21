<x-layouts.app
    title="Show Call Logs"
    subtitle="Listing the jail call logs for Donna Adelson."
>
    <x-ui.card class="mx-auto w-screen">


        @foreach($categories as $category)
                <p>{{$category}}</p>
        @endforeach



        <table class="table w-full text-xs" cellpadding="4">
            <thead>
                <tr class="text-left bg-blue-500">
                    <th>ID</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th>Comm Type</th>
                    <th>Comm Status</th>
                    <th>Termination Category</th>
                    <th>Other Party</th>
                    <th>Is Private</th>
                </tr>
            </thead>

            <tbody>
                @foreach($calls as $call)
                    <tr>
                        <td>
                            {{ $call->id }}
                        </td>
                        
                        <td>
                            {{ optional($call->start_time)->format('m/d/Y g:i:s A') }}
                        </td>
                        <td>
                            {{ optional($call->end_time)->format('m/d/Y g:i:s A') }}
                        </td>
             
                        <td>
                            {{ gmdate('H:i:s', $call->duration) }}
                        </td>
                      
                        <td>
                            {{ $call->comm_type }}
                        </td>
                        <td>
                            {{ $call->comm_status }}
                        </td>
                        <td>
                            {{ $call->termination_category }}
                        </td>

                        <td>
                            {{ $call->other_party }}
                        </td>
                        <td>
                            {{ $call->is_private }}
                        </td>


                       
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $calls->links() }}
    </x-ui.card>
</x-layouts.app>