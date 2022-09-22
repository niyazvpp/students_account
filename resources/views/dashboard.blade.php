@extends('layouts.dash')

@section('main')

   <div class="w-full">


       <div>

        <div class="sm:grid sm:grid-cols-2 px-4 sm:px-0">
            <div class="btn my-4 sm:my-0 flex justify-center flex-col sm:rounded-r-none bg-blue-400">
                <div class="text-2xl font-medium">₹ {{ $user->is('student') || $user->balance == 0 ? $user->balance : -($user->balance) }}</div>
                <div class="text-sm font-normal">Balance {{ !$user->is('student') ? 'in Hand' : '' }}</div>
            </div>
            <div>
                <div class="btn py-2 my-4 sm:my-0 flex justify-center flex-col sm:rounded-b-none sm:rounded-l-none bg-green-400">
                    <div class="text-xl font-medium">₹ {{ $user->is('student') ? $user->total_income : $user->total_expenses }}</div>
                        <div class="text-sm font-normal">{{ !$user->is('student') ? 'You Recieved' : 'Deposit' }}</div>
                    </div>
                <div class="btn py-2 my-4 sm:my-0 flex justify-center flex-col sm:rounded-t-none sm:rounded-l-none bg-red-400">
                    <div class="text-xl font-medium">₹ {{ !$user->is('student') ? $user->total_income : $user->total_expenses }}</div>
                        <div class="text-sm font-normal">{{ !$user->is('student') ? 'You Spent' : 'Expense' }}</div>
                    </div>
                </div>
            </div>
        </div>


        <a href="{{ route('transact') }}">
            <div class="underline text-blue-400 hover:text-blue-500 text-center mt-12">
                {{ auth()->user()->is('teacher') || auth()->user()->is('admin') ? 'Start' : 'View' }} Transactions
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>
    </div>


       @if($user->user_type == 'admin')

       <div class="card mt-6" x-data="app()">
            <div class="card-body overflow-x-auto">
                <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <form @submit.prevent="submit($event.target)" action="{{ route('ajax.students') }}" method="POST" autocomplete="off">
                    @csrf
                    <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900 capitalize">
                        Add Students in Bulk
                    </h3>


                        {{-- <div class="form-group">
                            <label for="students" class="btn bg-gray-200 border shadow-none border-gray-300 text-gray-600  block text-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Students</label>
                            <input name="students" id="students" class="hidden" accept=".xlsx" type="file">
                            <small class="text-red-500 error"></small>
                        </div> --}}

                        <div class="form-group">
                            <label for="students" class="label">Paste Students Excel</label>
                            <textarea name="students" id="students" class="input" rows="10"></textarea>
                            <small class="text-red-500 error"></small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-blue w-full flex items-center justify-center" :class="{ 'opacity-30': loading }" :disabled="loading">
                                <template x-if="loading">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                </template>
                                <span x-text="loading ? 'Processing...' : 'Process'"></span>
                            </button>
                        </div>

                    </form>

                    {{-- <button class="btn bg-red-400 text-white" @click.prevent="confirm('Sure to Delete All Students?') && $refs.deleteAll.submit()">
                        Delete All
                    </button>
                    <form x-ref="deleteAll" method="POST" action="{{ route('ajax.students.delete_all') }}">
                        @csrf
                    </form> --}}
                </div>
            </div>
        </div>

        <div class="card mt-6" x-data="transactions_upload()">
            <div class="card-body overflow-x-auto">
                <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <form @submit.prevent="submit($event.target)" action="{{ route('ajax.transactions') }}" method="POST" autocomplete="off">
                    @csrf
                    <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900 capitalize">
                        Add Transactions in Bulk
                    </h3>

                        <div class="form-group">
                            <label for="transactions" class="label">Paste Transactions Excel</label>
                            <textarea name="transactions" id="transactions" class="input" rows="10"></textarea>
                            <small class="text-red-500 error"></small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-blue w-full flex items-center justify-center" :class="{ 'opacity-30': loading }" :disabled="loading">
                                <template x-if="loading">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                </template>
                                <span x-text="loading ? 'Processing...' : 'Process'"></span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-6">
            <div class="card-body overflow-x-auto">
                <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <form action="{{ route('import') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900 capitalize">
                        Import From Json
                    </h3>


                        <div class="form-group">
                            <label for="json" class="btn bg-gray-200 border shadow-none border-gray-300 text-gray-600  block text-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Json</label>
                            <input name="json" id="json" class="hidden" accept=".json" type="file">
                            <small class="text-red-500 error"></small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-blue w-full flex items-center justify-center">
                                <span>Process</span>
                            </button>
                        </div>

                    </form>
                </div>

                <div x-data="exporter()" class="mt-4 flex items-center justify-center">
                    <button @click="exportXLSX()" type="button" class="btn btn-blue w-full flex items-center justify-center">
                        <span>Export Data</span>
                    </button>
                </div>

            </div>
        </div>

    <script src="{{ asset('js/xlsx.full.min.js') }}"></script>
    <script>

        function exporter() {
            return {
                excel_loading: false,
                exportXLSX() {
                    var formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('data', JSON.stringify({
                        action: 'transactions',
                        inputs: {
                            export: true
                        }
                    }));
                    let data = fetch('{{ route('ajax') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData,
                    }).then(json => {
                        return json.json();
                    }).then(data => {
                        this.exportXLSX_data(data.transactions);
                    });
                },
                exportXLSX_data(data) {
                    const rows = data.map(row => {
                        var date = this.dateData(row.created_at);
                        var transaction_type = row.sender.user_type == 'student' ? 'Deposit' : 'Expense';
                        return {
                            date: date, // new Date(row.created_at).toLocaleDateString(),
                            "admission_no": transaction_type == 'Deposit' ? row.sender.username : row.reciever.username,
                            name: transaction_type == 'Deposit' ? row.sender.name : row.reciever.name,
                            item: '',
                            deposit: transaction_type == 'Expense' ? '' : row.amount,
                            expense: transaction_type == 'Expense' ? row.amount : '',
                        }
                    });

                    /* generate worksheet and workbook */
                    const worksheet = XLSX.utils.json_to_sheet(rows);
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Transactions");

                    /* fix headers */
                    XLSX.utils.sheet_add_aoa(worksheet, [["Date", "Admission No", "Name", "Item", "Deposit", "Expense"]], { origin: "A1" });

                     /* calculate column width */
                    var max_width_array = [];
                    if (rows.length) {
                        var row = rows[0];
                        var keys = Object.keys(row);
                        keys.forEach(function(key, index) {
                            max_width_array.push({});
                            rows.forEach(function(row) {
                                max_width_array[index].wch = max_width_array[index].wch || 10;
                                if (row[key].toString().length > max_width_array[index].wch) {
                                    max_width_array[index].wch = row[key].toString().length + (10 * row[key].toString().length / 100);
                                }
                            });
                        });
                    }
                    worksheet["!cols"] = max_width_array;

                    /* create an XLSX file and try to save to Presidents.xlsb */
                    XLSX.writeFile(workbook, "Transactions List.xlsx");
                },
                dateData(date) {
                    var date = new Date(date);
                    return date.getDate() + '-' + this.month(date.getMonth()) + '-' + date.getFullYear() + ' ' + this.timeTo12HoursFormat(date);
                },
                timeTo12HoursFormat(time){
                    // convert hours to 12 hour format
                    var hours = time.getHours();
                    var minutes = time.getMinutes();
                    var ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12; // the hour '0' should be '12'
                    minutes = minutes < 10 ? '0'+minutes : minutes;
                    var strTime = hours + ':' + minutes + ' ' + ampm;
                    return strTime;
                },
                month(month) {
                    return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][month];
                }
            };
        }

        function app() {
            return {
                loading: false,
                reset() {
                    document.querySelectorAll(".form-group").forEach(formGroup => {
                        formGroup.classList.remove("validated");
                    });
                },
                calculated(data){
                    let returnData = [];
                    data = data.trim();
                    data.split('\n').forEach(row => {
                        if(row.length > 0){
                            let columns = row.split('\t');
                            if (columns[0] == '' || columns[1] == '' || columns[5] == '' || columns[3] == '')
                                return false;
                            let student = {
                                ad_no: columns[0],
                                name: columns[1],
                                class_id: columns[3],
                                mobile: columns[4] != '' && !isNaN(columns[4]) && columns[4] > 999999999 && columns[4] < 10000000000 ? columns[4] : '',
                                dob: columns[2],
                                old_balance: columns[5],
                            };
                            returnData.push(student);
                        }
                    });
                    console.log(returnData[0].dob);
                    return returnData;
                },
                loading2: false,
                truncate(form) {
                    if (this.loading2) return false;
                    this.loading2 = true;
                    fetch(form.action, {
                        body: new FormData(form),
                        method: form.method ? form.method : "POST",
                        headers: {
                        "Accept": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        console.log(json);
                        this.loading2 = false;
                        if (json.status == "success") {
                            this.$dispatch('alpine-show-message', {
                                type: 'success',
                                data: json.message,
                            });
                            setTimeout(() => {
                                window.location = '';
                            }, 1000);
                        }
                        if (json.errors) {
                            this.$dispatch('alpine-show-message', {
                                type: 'error',
                                data: json.message,
                            });
                        }
                    }).catch(e => {
                        console.log(e);
                        this.loading2 = false;
                    });
                },
                submit(form) {
                    if (this.loading || form.querySelector('[name="students"]').value =='') return false;
                    this.loading = true;
                    this.reset();
                    var data = new FormData();
                    data.append('_token', '{{ csrf_token() }}');
                    data.append('students', JSON.stringify(this.calculated(form.querySelector('[name="students"]').value)));
                    fetch(form.action, {
                        body: data,
                        method: form.method ? form.method : "POST",
                        headers: {
                        "Accept": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        console.log(json);
                        this.loading = false;
                        if (json.status == "success") {
                            this.$dispatch('alpine-show-message', {
                                type: 'success',
                                data: json.message,
                            });
                            form.querySelector('[name="students"]').value = '';
                        }
                        if (json.errors) {
                        Object.keys(json.errors).forEach(name => {
                            var obj = form.querySelector("[name=" + name +"]");
                            var error = json.errors[name][0];
                            obj.closest(".form-group").classList.add("validated");
                            obj.closest(".form-group").querySelector(".error").innerHTML = error;
                        });
                        }
                    }).catch(e => {
                        console.log(e);
                        this.loading = false;
                    });
                }
            };
        }

        function transactions_upload() {
            return {
                loading: false,
                reset() {
                    document.querySelectorAll(".form-group").forEach(formGroup => {
                        formGroup.classList.remove("validated");
                    });
                },
                calculated(data){
                    let returnData = [];
                    var count = 0;
                    var limit = 250;
                    data.split('\n').forEach(row => {
                        if(row.length > 0){
                            let columns = row.split('\t');
                            // console.log(columns, columns.length);
                            if (columns[0] == '' || columns[1] == '' || columns[2] == '' || (columns[4] == '' && columns[5] == ''))
                                return false;
                            count++;
                            let transaction = {
                                date: columns[0],
                                ad_no: columns[1],
                                category_name: columns[2],
                                description: columns[3],
                                transaction_type: columns[4] == '' ? 'expense' : 'deposit',
                                amount: columns[4] == '' ? columns[5] : columns[4],
                            };
                            if (count == ((limit * 1) + 1) || count == 1) {
                                count = 1;
                                returnData.push([]);
                            }
                            returnData[returnData.length-1].push(transaction);
                        }
                    });
                    console.log(returnData.length);
                    return returnData;
                },
                loading2: false,
                remaining: [],
                submit(form) {
                    if (this.loading || form.querySelector('[name="transactions"]').value =='') return false;
                    this.loading = true;
                    this.reset();
                    var calculated_array = this.calculated(form.querySelector('[name="transactions"]').value);
                    this.remaining = calculated_array;

                    if (!calculated_array.length) {
                        this.loading = false;
                        this.$dispatch('alpine-show-message', {
                            type: 'error',
                            data: 'No transactions found',
                        });
                        return false;
                    }
                    this.run_bulk_transactions(this.remaining[0], form);
                },

                run_bulk_transactions(calculated, form) {
                    var data = new FormData();
                    data.append('_token', '{{ csrf_token() }}');
                    data.append('transactions', JSON.stringify(calculated));
                    fetch(form.action, {
                        body: data,
                        method: form.method ? form.method : "POST",
                        headers: {
                        "Accept": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        console.log(json);
                        if (json.status == "success") {
                            this.remaining.shift();
                            if (!this.remaining.length) {
                                this.loading = false;
                                form.querySelector('[name="transactions"]').value = '';
                            } else {
                                this.run_bulk_transactions(this.remaining[0], form);
                            }
                            this.$dispatch('alpine-show-message', {
                                type: 'success',
                                data: json.message,
                            });
                        } else {
                            this.loading = false;
                        }
                    }).catch(e => {
                        console.log(e);
                        this.loading = false;
                    });
                }
            };
        }
    </script>

       @endif


   </div>

@endsection

