@extends('layouts.dash')

@section('main')

   <div class="sm:grid sm:grid-cols-2 w-full">


       <div class="space-y-6">

        <div class="card shadow-lg w-full">
            <div class="card-body">
                <h3 class="text-xl font-bold text-gray-600 mb-4 flex justify-start items-center">Overview <div class="text-gary-600 h-6 w-6 ml-1 inline-flex justify-center items-center bg-yellow-400 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </div></h3>
                <div class="sm:grid sm:grid-cols-12">
                    <div class="px-4 col-span-8">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
                                <g>
                                    <path style="fill:#5D647F;" d="M472,72H40C17.945,72,0,89.945,0,112v288c0,22.055,17.945,40,40,40h432c22.055,0,40-17.945,40-40    V112C512,89.945,494.055,72,472,72z"/>
                                </g>
                                <g>
                                    <path style="fill:#FFD100;" d="M176,232H80c-8.837,0-16-7.163-16-16v-64c0-8.837,7.163-16,16-16h96c8.837,0,16,7.163,16,16v64    C192,224.837,184.837,232,176,232z"/>
                                </g>
                                <g>
                                    <g>
                                        <path style="fill:#B8BAC0;" d="M120,336H80c-8.837,0-16-7.163-16-16v-8c0-8.837,7.163-16,16-16h40c8.837,0,16,7.163,16,16v8     C136,328.837,128.837,336,120,336z"/>
                                    </g>
                                    <g>
                                        <path style="fill:#B8BAC0;" d="M224,336h-40c-8.837,0-16-7.163-16-16v-8c0-8.837,7.163-16,16-16h40c8.837,0,16,7.163,16,16v8     C240,328.837,232.837,336,224,336z"/>
                                    </g>
                                    <g>
                                        <path style="fill:#B8BAC0;" d="M328,336h-40c-8.837,0-16-7.163-16-16v-8c0-8.837,7.163-16,16-16h40c8.837,0,16,7.163,16,16v8     C344,328.837,336.837,336,328,336z"/>
                                    </g>
                                    <g>
                                        <path style="fill:#B8BAC0;" d="M432,336h-40c-8.837,0-16-7.163-16-16v-8c0-8.837,7.163-16,16-16h40c8.837,0,16,7.163,16,16v8     C448,328.837,440.837,336,432,336z"/>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path style="fill:#8A8895;" d="M232,384H72c-4.422,0-8-3.582-8-8s3.578-8,8-8h160c4.422,0,8,3.582,8,8S236.422,384,232,384z"/>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path style="fill:#8A8895;" d="M336,384h-72c-4.422,0-8-3.582-8-8s3.578-8,8-8h72c4.422,0,8,3.582,8,8S340.422,384,336,384z"/>
                                    </g>
                                </g>
                                <g>
                                    <path style="fill:#FF4F19;" d="M368,216.002C359.211,225.821,346.439,232,332.224,232c-26.51,0-48-21.49-48-48s21.49-48,48-48    c14.213,0,26.983,6.177,35.772,15.993"/>
                                </g>
                                <g>
                                    <polygon style="fill:#FF9500;" points="192,192 112,192 112,176 192,176 192,160 112,160 112,136 96,136 96,232 112,232 112,208     192,208   "/>
                                </g>
                                <g>
                                    <circle style="fill:#FFD100;" cx="400" cy="184" r="48"/>
                                </g>
                            </g>

                        </svg>
                    </div>
                    <div class="px-3 col-span-4 sm:text-right text-center sm:border-l flex py-0 max-h-full justify-center flex-col">
                        <div class="text-3xl text-blue-400 font-bold">₹ 25000</div>
                        <div class="text-xs text-gray-400 mb-6">Current Balance</div>

                        <div class="text-2xl text-green-400 font-bold">₹ 25000</div>
                        <div class="text-xs text-gray-400 mb-6">Deposits</div>

                        <div class="text-2xl text-red-400 font-bold">₹ 25000</div>
                        <div class="text-xs text-gray-400 mb-6">Expenses</div>
                    </div>
                </div>

            </div>
        </div>


           <div class="card shadow-lg w-full">
               <div class="card-body w-full py-4 overflow-x-auto">
                   <h3 class="text-xl font-medium text-gray-600 mb-4">Latest Transactions</h3>
                   <table class="min-w-max w-full table-auto">
                       <thead>
                           <tr class="text-gray-400 font-light text-sm leading-normal">
                               <th class="font-normal text-left pr-3">Reciever/Sender</th>
                               <th class="font-normal text-left pr-3">Type</th>
                               <th class="font-normal text-left pr-3">Date</th>
                               <th class="font-normal text-left pr-3">Amount</th>
                           </tr>
                       </thead>
                       <tbody class="text-gray-300 text-sm font-light">
                           <tr class="border-b border-gray-100">
                               <td class="py-3 pr-3 text-left">
                                   <div class="inline-flex justify-start items-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                        </svg>
                                        <span class="text-gray-600 font-normal">Muhammed Niyaz</span>
                                   </div>
                               </td>
                               <td class="py-3 pr-3 text-left">NIOS Exam Fee</td>
                               <td class="py-3 pr-3 text-left">18/05/2021</td>
                               <td class="py-3 pr-3 text-left font-medium text-gray-600">NIOS Exam Fee</td>
                           </tr>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>



       @php
        $headers = ['a', 'b', 'c', 'd'];
        $data = [
            ['a', 'b', 'c', 'd'],
            ['a', 'b', 'c', 'd'],
            ['a', 'b', 'c', 'd'],
        ];
       @endphp

       <!-- <div class="card shadow-lg w-full">
           <div class="card-body w-full py-4">
               <h3 class="text-xl font-medium text-gray-600 mb-4">Latest Transactions</h3>
               <x-table :body="$data" :headers="$headers" />
           </div>
       </div> -->


   </div>

@endsection

