@extends('layouts.dash')

@section('main')

   <div class="w-full">


       <div>

        <div class="sm:grid sm:grid-cols-2 px-4 sm:px-0">
            <div class="btn my-4 sm:my-0 flex justify-center flex-col sm:rounded-r-none bg-blue-400">
                <div class="text-2xl font-medium">₹ {{ $user->balance }}</div>
                <div class="text-sm font-normal">Balance</div>
            </div>
            <div>
                <div class="btn py-2 my-4 sm:my-0 flex justify-center flex-col sm:rounded-b-none sm:rounded-l-none bg-green-400">
                    <div class="text-xl font-medium">₹ {{ $user->total_income }}</div>
                        <div class="text-sm font-normal">Deposit</div>
                    </div>
                <div class="btn py-2 my-4 sm:my-0 flex justify-center flex-col sm:rounded-t-none sm:rounded-l-none bg-red-400">
                    <div class="text-xl font-medium">₹ {{ $user->total_expenses }}</div>
                        <div class="text-sm font-normal">Expense</div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->is('teacher') || auth()->user()->is('admin'))
        <a href="{{ route('transact') }}">
            <div class="underline text-blue-400 hover:text-blue-500 text-center mt-12">
                Start Transactions
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>
        @endif
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

                <button class="btn bg-red-400 text-white" @click.prevent="confirm('Sure to Delete All Students?') && $refs.deleteAll.submit()">
                    Delete All
                </button>
                <form x-ref="deleteAll" method="POST" action="{{ route('ajax.students.delete_all') }}">
                    @csrf
                </form>
              </div>
        </div>
    </div>

    <script>
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
                            if (columns[0] == '' || columns[1] == '' || columns[2] == '' || columns[3] == '')
                                return false;
                            let student = {
                                ad_no: columns[0],
                                name: columns[1],
                                class_id: columns[2],
                                old_balance: columns[3],
                                user_type: 'student',
                            };
                            returnData.push(student);
                        }
                    });
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
    </script>

       @endif


   </div>

@endsection

