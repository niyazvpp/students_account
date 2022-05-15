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
                                <input step=".01" name="amount" id="amount" type="number" class="input py-4 text-xl bg-white">
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
                            <template x-for="transaction in showPaginatedTransactions()">
                                <tr class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                    <td @click="showTransaction(transaction)" class="py-3 pr-3 text-left">
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
                                    <td @click="showTransaction(transaction)" class="py-3 pr-3 text-left hidden">NIOS Exam Fee</td>
                                    <td @click="showTransaction(transaction)" class="py-3 pr-3 text-center hidden" x-text="dateData(transaction.created_at)">{{ date('d-M-y') }}</td>
                                    <td @click="showTransaction(transaction)" class="py-3 pr-3 text-center font-medium text-gray-600" x-text="transaction.amount"></td>
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
                    <template x-if="hasLoadMore()">
                        <div class="py-2 px-4 flex justify-center">
                            <button @click="loadMore()" class="btn border text-center block bg-gray-100 text-gray-500">
                                Load More
                            </button>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>


   </div>
</div>

<div x-data='modal()'
x-show="open" @view-transaction.window="show($event.detail)" class="fixed z-10 inset-0 overflow-y-auto modal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <!--
        Background overlay, show/hide based on modal state.

        Entering: "ease-out duration-300"
          From: "opacity-0"
          To: "opacity-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100"
          To: "opacity-0"
      -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" x-show="open"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"></div>

      <!-- This element is to trick the browser into centering the modal contents. -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <!--
        Modal panel, show/hide based on modal state.

        Entering: "ease-out duration-300"
          From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          To: "opacity-100 translate-y-0 sm:scale-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100 translate-y-0 sm:scale-100"
          To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      -->
      <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <form @submit.prevent="submit($event.target)" action="" method="POST">
            @csrf
            <input type="hidden" name="transaction" id="transaction" :value="transaction.id">
            <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900">
              Transaction Details
            </h3>

            <div class="space-y-4 py-4 text-gray-600">

                <div class="flex justify-between">
                    <div>Sent By</div>
                    <div class="font-medium" x-text="transaction.sender ? (transaction.sender.username + ' ' + transaction.sender.name) : data.user.name"></div>
                </div>

                <div class="flex justify-between">
                    <div>Recieved By</div>
                    <div class="font-medium" x-text="transaction.reciever ? (transaction.reciever.username + ' ' + transaction.reciever.name) : data.user.name"></div>
                </div>

                <div class="flex justify-between">
                    <div>Amount</div>
                    <div class="font-medium" x-text="'₹ ' + transaction.amount"></div>
                </div>

                <div class="flex justify-between">
                    <div>Time</div>
                    <div class="font-medium" x-text="dateData(transaction.created_at)"></div>
                </div>

                <div class="flex justify-between">
                    <div>Category</div>
                    <div class="font-medium" x-text="transaction.category ? transaction.category.name : ''"></div>
                </div>

                <div class="flex justify-between">
                    <div>Description</div>
                    <div class="font-medium" x-text="transaction.description"></div>
                </div>

                <div class="flex justify-between">
                    <div>Note</div>
                    <div class="font-medium" x-text="transaction.remarks"></div>
                </div>
            </div>

          </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button :disabled="loading" :class="{ 'hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-400': !loading, 'opacity-40': loading }" x-text="loading ? 'Loading...' : add ? 'Add Teacher' : 'Save Changes'" @click.prevent="submit($event.target.closest('.modal').querySelector('.form-container form'))" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2  text-base font-medium text-white bg-blue-400 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Add Teacher</button>
          <button @click="close()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fuse.js@6.5.3"></script>
  <script type="text/javascript">

    function modal() {
        return {
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
            open: false,
            add: true,
            loading: false,
            data: {
                students: [],
                user: {},
                transactions: []
            },
            dataAdded: false,
            transaction: {
                reciever: {},
                sender: {}
            },
            show(data) {
                if (!this.dataAdded) {
                    this.data = JSON.parse(JSON.stringify(data.data));
                    console.log(this.data);
                    this.dataAdded = true;
                }
                this.transaction = data.transaction;
                this.open = true;
            },
            close() {
                this.open = false;
            },
        }
    }

    function app() {
      return {
        students: {!! $students->toJson() !!},
        showTransaction(transaction) {
            this.$dispatch('view-transaction',
                {
                    data: {
                        transactions: this.transactions,
                        students: this.students,
                        user: this.user,
                    },
                    transaction: transaction
                }
            );
        },
        findStudent(id) {
            return this.students.find(student => student.id == id);
        },
        hasLoadMore() {
            return this.loaded_count < this.transactions.length;
        },
        loadMore() {
            this.loaded_count += 25;
        },
        loaded_count: 25,
        showPaginatedTransactions() {
            return this.transactions.filter((transaction, index) => index < this.loaded_count);
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
        if (this.ad_no != '' && (!isNaN(this.ad_no) || this.ad_no.trim() != '')) {
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
                var new_balance = this.students.find(student => student.id == this.student.id).balance * 1 + (this.transaction_type == 'deposit' ? json.transaction.amount : -(json.transaction.amount)) * 1;
                this.students.find(student => student.id == this.student.id).balance = new_balance;
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
                var new_balance = this.students.find(student => student.id == this.student.id).balance * 1 + (this.transaction_type == 'deposit' ? json.transaction.amount : -(json.transaction.amount)) * 1;
                this.students.find(student => student.id == this.student.id).balance = new_balance;
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
