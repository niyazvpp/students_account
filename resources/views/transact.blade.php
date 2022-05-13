@extends('layouts.dash')

@section('main')
<div class="" x-data="app()">

   <div class="w-full sm:px-0 px-2">

    <div class="sm:grid sm:grid-cols-12">


        <div class="col-span-3 order-last pl-4 sm:pr-0 pr-4 pb-4 sm:pb-0">
            <div class="justify-center flex">
                <button @click="transaction_type = 'deposit'" class="btn block w-full rounded-r-none bg-green-400 focus:outline-none" :class="{ 'not-active bg-gray-300': transaction_type != 'deposit' }">
                    Deposit
                </button>
                <button @click="transaction_type = 'expense'" class="btn block w-full bg-red-400 focus:outline-none rounded-l-none" :class="{ 'not-active bg-gray-300': transaction_type != 'expense' }">
                    Expense
                </button>
            </div>
        </div>

        <div class="col-span-9">

            <div class="card">
                <div class="card-body overflow-x-auto">
                    <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <form @submit.prevent="submit($event.target)" action="" method="POST" autocomplete="off">
                          @csrf
                          <input type="hidden" name="other_id" :value="student.id">
                          <input type="hidden" name="transaction_type" :value="transaction_type">
                          <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900 capitalize">
                            New <span x-text="transaction_type"></span>
                          </h3>

                          <div class="sm:grid sm:grid-cols-2">

                            <div class="form-group relative">
                                <label for="ad_no" class="label">Admission No:</label>
                                <input @input="ad_no = $event.target.value; stop = false; student = getStudent()" :value="ad_no" name="ad_no" id="ad_no" type="text" class="input">
                                <div x-show="ad_no && !stop && !student.name" class="shadow-lg py-1 bg-white absolute border right-3 left-0 rounded-lg">
                                    <ul class="overflow-y-auto" style="max-height: 100px;">
                                      <template x-for="result in searchResults()">
                                        <li @click="ad_no = result.meta.ad_no; stop = true; student = getStudent()" class="border-b py-1 cursor-pointer px-2 py-1 hover:bg-gray-50 text-sm" x-text="result.meta.ad_no + ' - ' + result.name + ' - ' + result.meta.class.name"></li>
                                      </template>
                                      <template x-if="!searchResults().length">
                                        <li class="px-2 py-1 text-sm" x-text="'No Result Found!'"></li>
                                      </template>
                                    </ul>
                                  </div>
                                <small class="text-red-500 error"></small>
                              </div>


                              <div class="form-group">
                                <label for="balance" class="label">Balance:</label>
                                <input name="balance" id="balance" disabled :value="student.balance" type="text" class="input bg-gray-200">
                                <small class="text-red-500 error"></small>
                              </div>

                          </div>

                            <div class="form-group">
                              <label for="name" class="label">Name:</label>
                              <input name="name" id="name" disabled :value="student.name" type="text" class="input bg-gray-200">
                              <small class="text-red-500 error"></small>
                            </div>

                            <div class="form-group">
                                <label for="amount" class="label">Amount:</label>
                                <input name="amount" id="amount" type="number" class="input py-4 text-xl bg-white">
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


            <div class="card shadow-lg w-full mt-12 capitalize">
                <div class="card-body w-full py-4 overflow-x-auto">
                    <h3 class="text-xl font-medium text-gray-600 mb-4">Latest Transactions</h3>
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="text-gray-400 font-light text-sm leading-normal">
                                <th class="font-normal text-left pr-3">
                                    <div class="inline-flex justify-start items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 invisible" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                        </svg>
                                    Student
                                    </div>
                                </th>
                                <th class="font-normal text-center pr-3 hidden">Type</th>
                                <th class="font-normal text-center pr-3 hidden">Date</th>
                                <th class="font-normal text-center pr-3">Amount</th>
                                <th class="font-normal text-center pr-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-300 text-sm font-light">
                            <template x-for="transaction in transactions">
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 pr-3 text-left">
                                        <div class="inline-flex justify-start items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" :class="{ 'text-red-600 rotate-180': transaction.sender_id == user.id, 'text-green-600': transaction.sender_id != user.id }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                            <div>
                                                <div class="text-gray-600 font-normal" x-text="transaction.sender_id == user.id ? findStudent(transaction.reciever_id).meta.ad_no + ' ' + findStudent(transaction.reciever_id).name : findStudent(transaction.sender_id).meta.ad_no + ' ' + findStudent(transaction.sender_id).name"></div>
                                                <div class="font-normal" x-text="dateData(transaction.created_at)"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-3 text-left hidden">NIOS Exam Fee</td>
                                    <td class="py-3 pr-3 text-center hidden" x-text="dateData(transaction.created_at)">{{ date('d-M-y') }}</td>
                                    <td class="py-3 pr-3 text-center font-medium text-gray-600" x-text="transaction.amount"></td>
                                    <td class="py-3 pr-3 text-center font-medium text-gray-600">
                                        <form @submit.prevent="confirm('Are you sure to delete this transaction?') && deleteTransaction($event.target)" method="POST" action="{{ route('transactions.delete') }}">
                                            @csrf
                                            <input type="hidden" name="delete_id" :value="transaction.id">
                                            <button type="submit" class="btn block mx-auto text-white bg-red-500">

                                                <svg x-show="loading2 != transaction.id" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <svg x-show="loading2 == transaction.id" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


   </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/fuse.js@6.5.3"></script>
  <script type="text/javascript">
    function app() {
      return {
        students: {!! $students->toJson() !!},
        findStudent(id) {
            return this.students.find(student => student.id == id);
        },
        transactions: {!! $transactions->toJson() !!},
        user: {!! $user->toJson() !!},
        student: {
            meta: {
              ad_no: '',
              class: {}
            },
        },
        userDetails() {
            return this.transactions.reduce((acc, curr) => {
                if (curr.transaction_type == 'expense') {
                    acc.deposit += curr.amount;
                    acc.balance += curr.amount;
                } else {
                    acc.expense += curr.amount;
                    acc.balance -= curr.amount;
                }
                return acc;
            }, {
                deposit: 0,
                expense: 0,
                balance: 0
            });
        },
        stop: false,
        ad_no: '',
        loading: false,
        transaction_type: 'deposit',
        getStudent() {
            return this.students.filter(student => student.meta.ad_no == this.ad_no)[0] || {
            meta: {
              ad_no: '',
              class: {}
            }
            };
        },
        searchResults() {
        var data = [];
        if (this.ad_no > 0) {
                const options = {
                shouldSort: true,
                keys: ['name', 'meta.ad_no', 'class.name'],
                threshold: 0
            };
            const fuse = new Fuse(this.students, options);
            data = fuse.search(this.ad_no).map(result => result.item);
        }
        return data;
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
        },
        reset() {
        document.querySelectorAll(".form-group").forEach(formGroup => {
            formGroup.classList.remove("validated");
        });
        },
        loading2: false,
        deleteTransaction(form) {
            if (this.loading2 == form.querySelector('[name="delete_id"]').value) return false;
          this.loading2 = form.querySelector('[name="delete_id"]').value;
          fetch(form.action, {
            body: new FormData(form),
            method: form.method ? form.method : "POST",
            headers: {
              "Accept": "application/json",
            }
          })
          .then(res => res.json())
          .then(json => {
            this.loading2 = false;
            if (json.status == "success") {
                this.$dispatch('alpine-show-message', {
                    type: 'success',
                    data: 'Transaction Deleted Successfully!',
                });
                this.transactions = this.transactions.filter(t => t.id != form.querySelector('[name="delete_id"]').value);
            }
            if (json.errors) {
                this.$dispatch('alpine-show-message', {
                    type: 'error',
                    data: 'Internal Server Error',
                });
            }
          }).catch(e => {
            console.log(e);
            this.loading2 = false;
          });
        },
        submit(form) {
          if (!this.student.id) {
              this.$dispatch('alpine-show-message', {
                type: 'error',
                data: 'Please Select An Existing Student',
              });
              return false;
          }
          if (this.loading) return false;
          this.loading = true;
          this.reset();
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
            this.loading = false;
            if (json.status == "success") {
                this.$dispatch('alpine-show-message', {
                    type: 'success',
                    data: 'Transaction Success!',
                });
                this.transactions.unshift(json.transaction);
                this.ad_no = '';
                form.querySelector('input[name="ad_no"]').value = '';
                form.querySelector('input[name="amount"]').value = '';
                this.student = {
                    meta: {
                      ad_no: '',
                      class: {}
                    }
                };
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
    }
    }
  </script>

@endsection
