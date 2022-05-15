<table class="min-w-max w-full table-auto">
   <thead>
       <tr class="text-gray-400 font-light border-b text-sm leading-normal">
           @foreach ($headers as $header)
                <th class="font-medium text-left py-3 pr-3">{{ $header }}</th>
           @endforeach
       </tr>
   </thead>
   <tbody class="text-gray-300 text-sm font-light">
        @foreach ($body as $tr)
            <tr {{ $tr_attributes }} class="border-b border-gray-100">
                @foreach ($tr as $td)
                    <td class="py-4 pr-3 text-left text-gray-700">{!! $td !!}</td>
                @endforeach
            </tr>
        @endforeach
   </tbody>
</table>
