@extends('layouts.dash')

@section('main')
<div class="" x-data="app()" @refresh-transactions.window="firstLoading = true; loadTransactions()">

   <div class="w-full sm:px-0 px-2">

    @if(!$user->is('student'))<div class="sm:grid sm:grid-cols-12">


        <div class="col-span-3 order-last pl-4 sm:pr-0 pr-4 pb-4 sm:pb-0">
            <button @click.prevent="transaction_type = false" class="btn mb-3 block w-full border-2 border-blue-400 focus:outline-none" :class="{ 'not-active bg-white shadow-md text-gray-800': transaction_type, 'bg-blue-400': !transaction_type  }">
                Search
            </button>
            <div class="justify-center flex">
                <button @click.prevent="transaction_type = 'deposit'" class="btn block w-full border-2 border-green-400 rounded-r-none  focus:outline-none" :class="{ 'not-active bg-white shadow-md text-gray-800': transaction_type != 'deposit', 'bg-green-400': transaction_type == 'deposit'  }">
                    Deposit
                </button>
                <button @click.prevent="transaction_type = 'expense'" class="btn block w-full border-2 border-red-400 focus:outline-none  rounded-l-none" :class="{ 'not-active bg-white shadow-md text-gray-800': transaction_type != 'expense', 'bg-red-400': transaction_type == 'expense'  }">
                    Expense
                </button>
            </div>
        </div>


            <div class="col-span-9 relative">

                <div class="card mb-12">
                    <div class="card-body overflow-x-auto">

                        <!-- Transact Section -->
                        <template x-if="transaction_type">
                            <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <form @submit.prevent="submit($event.target)" action="" method="POST" autocomplete="off">
                                @csrf
                                <input type="hidden" name="other_id" :value="student.id">
                                <input type="hidden" name="transaction_type" :value="transaction_type">
                                <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900 capitalize flex justify-between"><div>New <span x-text="transaction_type"></span></div> <button @click.prevent="filters_show2 = !filters_show2" class="text-sm font-medium text-gray-600 flex items-center btn bg-gray-100 ring-2 ring-gray-200">
                                        <svg x-show="filters_show2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                        <svg x-show="!filters_show2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="ml-2" x-text="(filters_show2 ? 'Hide' : 'Show') + ' Filters'"></span>
                                        </button>
                                    </h3>

                                <div x-show="filters_show2" x-transition class="mb-3 sm:flex flex-wrap">
                                        <div class="mb-3 flex items-center">
                                            <select class="input block w-full mb-0 pr-10 border-2 border-gray-300" @change="changeStudentsSetting('class_id', $event.target.value, false)">
                                                <option value="">All Classes</option>
                                                    <template x-for="classe in classes">
                                                        <option :value="classe.id" :selected="classe.id == transaction_settings.class_id" x-text="classe.name"></option>
                                                    </template>
                                            </select>
                                        </div>
                                        <div class="mb-3 sm:ml-5 text-gray-600 text-sm flex items-center">
                                            <input id="show_advanced" @change="show_advanced = $event.target.checked" :value="1" type="checkbox" class="text-blue-400 rounded mr-2 ring-2 border-0 ring-gray-300 focus:outline-none focus:ring-blue-400">
                                            <label for="show_advanced">Show Advanced Options</label>
                                        </div>
                                    </div>

                                {{-- <div class="sm:grid sm:grid-cols-2"> --}}

                                    <div class="form-group relative">
                                        <label for="ad_no" class="label">Search</label>
                                        <input placeholder="Ad. No, Username or Name" @input.debounce="ad_no = trimAndLowerCaseIfString($event.target.value); stop = false; searchStudents()" :value="ad_no" name="ad_no" id="ad_no" type="text" class="input">
                                        <div x-show="ad_no && !stop && !student.name" class="shadow-lg py-1 bg-white z-10 absolute border right-3 left-0 rounded-lg">
                                            <ul class="overflow-y-auto">
                                            <template x-for="(result, i) in students">
                                                <li @click.prevent="!inTag(result.id) && addTag(result.id)" :class="{ 'bg-gray-100 cursor-not-allowed text-gray-400': inTag(result.id) }" class="border-b flex cursor-pointer px-2 py-1 hover:bg-gray-100 text-sm">
                                                    <svg x-show="inTag(result.id)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span x-html="(result.user_type == 'student' ? result.username + ' ' : '') + result.name + (result.user_type == 'student' ? (result.meta && result.meta.class ? ' ' + result.meta.class.name : '') : '') + ' - Bal. ' + '<strong class=\'' + (result.balance > 100 ? 'text-gray-800' : (result.balance < 0 ? 'text-red-600' : 'text-yellow-600')) + '\'>' + result.balance + '</strong>' + '₹'" class="ml-2"></span>
                                                </li>
                                            </template>
                                            <template x-if="!students.length">
                                                <li class="px-2 py-1 text-sm" x-text="loading ? 'Searching...' : 'No Result Found!'"></li>
                                            </template>
                                            </ul>
                                        </div>
                                        <template x-for="(tag, index) in addedStudents()" :key="tag.id">
                                            <div class="bg-indigo-100 inline-flex items-center text-sm rounded mt-2 mr-1">
                                            <span class="ml-2 mr-1 leading-relaxed" x-html="(tag.user_type == 'student' ? tag.username + ' ' : '') + tag.name + (tag.user_type == 'student' ? (tag.meta && tag.meta.class ? ' ' + tag.meta.class.name : '') : '') + ' - Bal. ' + '<strong class=\'' + (tag.balance > 100 ? 'text-gray-800' : (tag.balance < 0 ? 'text-red-600' : 'text-yellow-600')) + '\'>' + tag.balance + '</strong>' + '₹'"></span>
                                            <button type="button" @click.prevent="removeTag(index)" class="w-6 h-8 inline-block align-middle text-gray-500 hover:text-gray-600 focus:outline-none">
                                                <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
                                            </button>
                                            </div>
                                        </template>
                                        <small class="text-red-500 error"></small>
                                    </div>

                                    <div x-show="show_advanced" class="form-group text-gray-600 text-xs flex items-center">
                                        <input id="show_advanced_exclude" @change="advanced_fields.exclude = $event.target.checked" :value="1" type="checkbox" class="text-blue-400 rounded mr-2 ring-2 border-0 ring-gray-300 focus:outline-none focus:ring-blue-400">
                                        <label for="show_advanced_exclude">Include All Students from the selected class and Exclude Above Selected People Only</label>
                                    </div>

                                    <div x-show="show_advanced && advanced_fields.exclude" class="form-group sm:col-span-9">
                                        <label for="applicable_to" class="label">Applicable To:</label>
                                        <input name="applicable_to" id="name" disabled :value="advanced_fields.class_text" type="text" class="input bg-gray-200">
                                        <small class="text-red-500 error"></small>
                                    </div>

                                <div class="form-group relative">
                                    <label for="category" class="label">Category:</label>
                                    <input type="hidden" name="category_id" :value="isNaN(category_id) ? category.id : category_id">
                                    <input type="hidden" name="new" :value="category_new">
                                    <input required @focus="focused = true" @blur.debounce.500ms="focused = false" @input="category_name = $event.target.value; stopCategorySearch = false; category = getCategory(); category_id = getCategory().id" :value="category_name" name="category_name" id="category_name" type="text" class="input">
                                    <div x-show="(category_name && !stopCategorySearch && !category.name) || (focused && !stopCategorySearch && !category.name)" class="shadow-lg py-1 bg-white z-10 absolute border right-3 left-0 rounded-lg">
                                        <ul class="overflow-y-auto bg-white" style="max-height: 100px;">
                                        <template x-for="result in categoryResults()">
                                            <li @click="category_name = result.name; category_id = result.id; stopCategorySearch = true; category = getCategory(); category_new = result.new || ''" class="border-b py-2 cursor-pointer px-2 hover:bg-gray-50 text-sm">
                                                <span x-text="result.name"></span>
                                                <small class="text-green-500 font-normal text-xs" x-show="result.new == 'new'">(Create New)</small>
                                            </li>
                                        </template>
                                        <template x-if="!categoryResults().length">
                                            <li class="px-2 py-2 text-sm" x-text="'No Result Found!'"></li>
                                        </template>
                                        </ul>
                                    </div>
                                    <small class="text-green-500 font-normal text-xs" x-show="category_new == 'new'">Creating New Category</small>
                                    <small class="text-red-500 error"></small>
                                </div>

                                    <div class="form-group">
                                        <label for="description" class="label">Description: (Optional)</label>
                                        <textarea name="description" id="description" class="input bg-white"></textarea>
                                        <small class="text-red-500 error"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="amount" class="label">Amount:</label>
                                        <input required @input="advanced_amount = ($event.target.value ?? 0)" x-ref="amount_value" step=".01" name="amount" id="amount" type="number" class="input py-4 text-xl bg-white">
                                        <small class="text-red-500 error"></small>
                                    </div>

                                    <div x-show="show_advanced && transaction_type == 'expense'" class="form-group text-gray-600 text-xs flex items-center">
                                        <input id="show_advanced_divide" @change="advanced_fields.divide = $event.target.checked" :value="1" type="checkbox" class="text-blue-400 rounded mr-2 ring-2 border-0 ring-gray-300 focus:outline-none focus:ring-blue-400">
                                        <label for="show_advanced_divide">Divide Amount Equally Between Included People. Deducted will be rounded and remaining will be deposited to library account.</label>
                                    </div>

                                    <div x-show="show_advanced && advanced_fields.divide" class="form-group sm:col-span-9">
                                        <label for="applicable_amount" class="label">Applicable To Each:</label>
                                        <input name="applicable_amount" id="applicable_amount" disabled :value="advanced_applicable_amount()" type="text" class="input bg-gray-200">
                                        <small class="text-red-500 error"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="total_amount" class="label ">Total Amount:</label>
                                        <input x-ref="total_amount" step=".01" name="total_amount" disabled :value="advanced_total_amount()" id="total_amount" type="number" class="input py-4 text-xl bg-white">
                                        <small class="text-red-500 error"></small>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-blue w-full flex items-center justify-center" :class="{ 'opacity-60': loading }" :disabled="loading">
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

                        </template>

                        <template x-if="!transaction_type">
                            <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                  <input type="hidden" name="other_id" :value="student2.id">
                                  <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900 capitalize flex justify-between"><div>Search {{ !$user->isAdmin() ? 'Student' : 'User' }}</div> <button @click.prevent="filters_show2 = !filters_show2" class="text-sm font-medium text-gray-600 flex items-center btn bg-gray-100 ring-2 ring-gray-200">
                                        <svg x-show="filters_show2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                        <svg x-show="!filters_show2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="ml-2" x-text="(filters_show2 ? 'Hide' : 'Show') + ' Filters'"></span>
                                        </button>
                                    </h3>

                                  <div x-show="filters_show2" x-transition class="mb-3 sm:flex flex-wrap">
                                        <div class="mb-3 flex items-center">
                                            <select class="input block w-full mb-0 pr-10 border-2 border-gray-300" @change="changeStudentsSetting2('class_id', $event.target.value, false)">
                                                <option value="">All Classes</option>
                                                    <template x-for="classe in classes">
                                                        <option :value="classe.id" :selected="classe.id == transaction_settings.class_id" x-text="classe.name"></option>
                                                    </template>
                                            </select>
                                        </div>
                                    </div>

                                  {{-- <div class="sm:grid sm:grid-cols-2"> --}}
                                    <form @submit.prevent="" class="block">
                                        <div class="form-group">
                                            <label for="ad_no2" class="label">Search {{ !$user->isAdmin() ? 'Student' : 'User' }}</label>
                                            <input @input.debounce="ad_no2 = trimAndLowerCaseIfString($event.target.value); stop2 = false; searchStudents2()" :value="ad_no2" name="ad_no2" id="ad_no2" type="text" class="input">
                                            <div x-show="ad_no2 && !stop2 && !student2.name" class="shadow-lg py-1 bg-white z-20 absolute border right-6 left-6 rounded-lg">
                                                <ul>
                                                <template x-for="(result, i) in students2">
                                                    <li @click.prevent="()=> {if(result.id != student2.id) { student2 = result; transaction_settings.search_user = result.id; loadTransactions(); ad_no2 = ''; }}" :class="{ 'bg-gray-100 cursor-not-allowed text-gray-400': result.id == student2.id }" class="border-b flex cursor-pointer px-2 py-1 hover:bg-gray-100 text-sm">
                                                        <svg x-show="result.id == student2.id" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span x-html="(result.user_type == 'student' ? result.username + ' ' : '') + result.name + (result.user_type == 'student' ? (result.meta && result.meta.class ? ' ' + result.meta.class.name : '') : '') + ' - Bal. ' + '<strong class=\'' + (result.balance > 100 ? 'text-gray-800' : (result.balance < 0 ? 'text-red-600' : 'text-yellow-600')) + '\'>' + result.balance + '</strong>' + '₹'" class="ml-2"></span>
                                                    </li>
                                                </template>
                                                <template x-if="!students2.length">
                                                    <li class="px-2 py-1 text-sm" x-text="loading3 ? 'Searching...' : 'No Result Found!'"></li>
                                                </template>
                                                </ul>
                                            </div>
                                        </div>
                                    </form>

                                    <template x-if="student2.name">
                                        <div class="space-y-4 py-4 text-gray-600">
                                            <div class="flex justify-between">
                                                <div>Name</div>
                                                <div class="font-medium capitalize" x-text="student2.name"></div>
                                            </div>

                                            <div class="flex justify-between">
                                                <div x-text="student2.user_type == 'student' ? 'Ad No.' : 'Username'">Username</div>
                                                <div class="font-medium" x-text="student2.username"></div>
                                            </div>

                                            <div x-show="student2.user_type == 'student'" class="flex justify-between">
                                                <div>Class</div>
                                                <div class="font-medium capitalize" x-text="student2.meta && student2.meta.class ? student2.meta.class.name : 'N/A'"></div>
                                            </div>

                                            <!-- Expenses and Deposits -->
                                            <div class="flex justify-between">
                                                <div x-text="student2.user_type == 'student' ? 'Expenses' : 'Recieved'">Expenses</div>
                                                <div class="font-medium capitalize" x-text="(student2.user_type != 'student' ? student2.total_income : student2.total_expenses) + '₹'"></div>
                                            </div>

                                            <div class="flex justify-between">
                                                <div x-text="student2.user_type == 'student' ? 'Deposits' : 'Spent'">Deposits</div>
                                                <div class="font-medium capitalize" x-text="(student2.user_type == 'student' ? student2.total_income : student2.total_expenses) + '₹'"></div>
                                            </div>

                                            <div class="flex justify-between font-semibold">
                                                <div>Balance <span x-text="student2.user_type == 'student' ? '' : 'in Hand'"></span></div>
                                                <div class="capitalize" x-text="(student2.user_type == 'student' ? student2.balance : -(student2.balance)) + '₹'"></div>
                                            </div>
                                        </div>
                                    </template>


                              </div>
                        </template>

                    </div>
                </div>@endif

                <div class="card shadow-lg w-full mt-4 capitalize">
                    <div class="card-body w-full py-4 overflow-x-auto">
                        <h3 class="text-xl font-medium text-gray-600 mb-4 flex justify-between"><div>Latest Transactions</div> <button @click.prevent="filters_show = !filters_show" class="text-sm font-medium text-gray-600 flex items-center btn bg-gray-100 ring-2 ring-gray-200">
                            <svg x-show="filters_show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <svg x-show="!filters_show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="ml-2" x-text="(filters_show ? 'Hide' : 'Show') + ' Filters'"></span>
                        </button></h3>
                        <div x-show="filters_show" x-transition class="mb-3 grid sm:flex items-center sm:flex-wrap sm:justify-start">

                            <div class="mb-2 sm:mr-2 flex">
                                <button @click.prevent="changeTransactionSetting('type', 'deposits', true)" class="btn py-1 px-3 border-2 border-gray-400 rounded-r-none  focus:outline-none block w-full" :class="{ 'not-active bg-white shadow-md text-gray-800': transaction_settings.type != 'deposits', 'bg-gray-500': transaction_settings.type == 'deposits'  }">
                                    Deposit
                                </button>
                                <button @click.prevent="changeTransactionSetting('type', 'expenses', true)" class="btn py-1 px-3 border-2 border-gray-400 focus:outline-none  rounded-l-none block w-full" :class="{ 'not-active bg-white shadow-md text-gray-800': transaction_settings.type != 'expenses', 'bg-gray-500': transaction_settings.type == 'expenses'  }">
                                    Expense
                                </button>
                            </div>
                            <div class="sm:mr-2 mb-2 flex items-center">
                                <input type="date" class="input py-2 mb-0 border-2 border-gray-300" placeholder="Start Date" @change="changeTransactionSetting('date_from', $event.target.value, false, transaction_settings.date_to)">
                                <div class="flex items-center h-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block text-gray-400 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <input type="date" class="input py-2 mb-0 border-2 border-gray-300" placeholder="End Date" @change="changeTransactionSetting('date_to', $event.target.value, false, transaction_settings.date_from)">
                            </div>
                            <div class="mb-2 sm:mr-2 flex">
                                <input @input.debounce="changeTransactionSetting('search', $event.target.value, false, false)" type="search" placeholder="Search..." class="input py-2 mb-0 px-3 border-2 rounded-r-none focus:ring-0 border-gray-300">
                                <button :disabled="firstLoading" @click.prevent="!firstLoading && (firstLoading = true, loadTransactions())" :class="{'noactive': firstLoading}" class="btn py-2 px-3 btn-blue rounded-l-none justify-self-end focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            @if (!$user->is('student'))
                            <div class="mr-2 mb-2 flex text-left">
                                <select class="input mr-2 py-2 pr-10 mb-0 border-2 border-gray-300" @change="changeTransactionSetting('user_id', $event.target.value, false)">
                                    <option value="yes">Mine Only</option>
                                    <option value="">All People</option>
                                </select>
                                <select class="input py-2 pr-10 mb-0 border-2 border-gray-300" @change="changeTransactionSetting('class_id', $event.target.value, false)">
                                    <option value="">All Classes</option>
                                        <template x-for="classe in classes">
                                            <option :value="classe.id" :selected="classe.id == transaction_settings.class_id" x-text="classe.name"></option>
                                        </template>
                                </select>
                            </div>
                            @endif
                            <div class="sm:mr-2 mb-2">
                                <select class="input py-2 pr-10 mb-0 border-2 border-gray-300" @change="changeTransactionSetting('category_id', $event.target.value, false)">
                                    <option value="">All Categories</option>
                                        <template x-for="category in categories">
                                            <option :value="category.id" :selected="category.id == transaction_settings.category_id" x-text="category.name"></option>
                                        </template>
                                </select>
                            </div>
                        </div>

                        <template x-if="student2.id">
                            <div class="inline-flex items-center text-gray-600 mb-2 text-sm rounded mt-2 mr-1">
                                <span class="font-medium text-lg">Showing Transactions of:</span><span class="ml-2 mr-1 font-medium leading-relaxed" x-html="(student2.user_type == 'student' ? student2.username + ' ' : '') + student2.name + (student2.user_type == 'student' ? (student2.meta && student2.meta.class ? ' ' + student2.meta.class.name : '') : '')"></span>
                                <button type="button" @click.prevent="student2 = false; delete transaction_settings.search_user; loadTransactions()" class="w-6 h-8 flex items-center text-red-500 hover:text-red-600 focus:outline-none">
                                    <svg class="ml-1 w-6 h-6 fill-current block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
                                </button>
                            </div>
                        </template>
                        <table class="w-full max-w-full table-fixed">
                            <thead>
                                <tr class="text-white py-4 bg-blue-500 font-light text-sm">
                                    <th class="font-normal text-left pr-3" style="width: 75%;">
                                        <div class="inline-flex justify-start items-center w-full">

                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transacted By
                                        </div>
                                    </th>
                                    <th class="font-normal text-center pr-3" style="width: {{ !$user->is('student') ? 25/2 : 25 }}%;">Amount</th>
                                    @if(!$user->is('student'))<th class="font-normal text-center pr-3" style="width: {{ 25/2 }}%;">Action</th>@endif
                                </tr>
                            </thead>
                            <tbody class="text-gray-400 text-sm font-light">
                                <template x-if="!transactions.length">
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                        <td colspan="5" class="py-3 pr-3 text-center text-gray-500" x-text="firstLoading ? 'Loading...' : 'No Transactions!'">

                                        </td>
                                    </tr>
                                </template>
                                <template x-for="(transaction, tci) in transactions">
                                    <tr :class="{ 'bg-gray-100': tci/2 == Math.round(tci/2) }" class="border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                        <td @click="showTransaction(transaction)" style="width: 75%;" class="py-3 pr-3 text-left">
                                            <div class="inline-flex justify-start items-center w-max-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" :class="{ 'rotate-180': transaction.sender_id == user.id, 'text-red-600': viewable(transaction).type == 'expense', 'text-green-600': viewable(transaction).type == 'deposit' }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                                </svg>
                                                <div>
                                                    <div class="text-gray-600 max-w-fit font-normal truncate" x-text="transaction.sender_id == user.id ? (transaction.reciever.user_type != 'student' ? '' : transaction.reciever.username + ' ') + transaction.reciever.name : (transaction.sender.user_type != 'student' ? '' : transaction.sender.username + ' ') + transaction.sender.name"></div>
                                                    <div class="font-normal" x-text="dateData(transaction.created_at)"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td @click="showTransaction(transaction)" class="py-3 pr-3 text-center font-medium text-gray-600" x-text="(transaction.amount ?? 0) - (transaction.remarks ?? 0)"></td>
                                        @if(!$user->is('student'))<td class="py-3 pr-3 text-center font-medium text-gray-600">
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
                                        </td>@endif
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <template x-if="hasLoadMore()">
                            <div class="py-2 px-4 flex justify-center">
                                <button @click="loadMore()" :disabled="lastTransactionDetails.loading" :class="{ 'opacity-40 focus:ring-0': lastTransactionDetails.loading }" x-text="lastTransactionDetails.loading ? 'Loading...' : 'Load More'" class="btn border text-center block bg-gray-100 text-gray-500">
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                @if(!$user->is('student'))
            </div>
    </div>@endif


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
      <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full sm:my-8 sm:align-middle sm:max-w-xl sm:w-full"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="bg-white form-container px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <form @submit.prevent="submit($event.target)" action="{{ route('transactions.update') }}" method="POST">
            @csrf
            <input type="hidden" name="transaction" id="transaction" :value="transaction.id">
            <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900">
              Transaction Details:
            </h3>

            <div class="space-y-4 py-4 text-gray-600">
                <div class="flex justify-between">
                    <div>Type</div>
                    <div class="font-medium capitalize" x-text="viewable(transaction).type"></div>
                </div>

                <div class="flex justify-between">
                    <div>Sent By</div>
                    <div class="font-medium" x-text="viewable(transaction).sender"></div>
                </div>

                <div class="flex justify-between">
                    <div>Recieved By</div>
                    <div class="font-medium" x-text="viewable(transaction).reciever"></div>
                </div>

                <div class="flex justify-between">
                    <div>Class</div>
                    <div class="font-medium" x-text="viewable(transaction).class || 'N/A'"></div>
                </div>

                <div class="flex justify-between items-center">
                    <div>Amount</div>
                    <div class="font-medium" x-text="'₹ ' + viewable(transaction).amount()"></div>
                </div>

                <div x-show="data.user.user_type != 'student' || transaction.remarks > 0" class="flex justify-between items-center">
                    <div>Cash</div>
                    @if (!$user->is('student'))
                        <input type="number" @change="updateTransaction('amount', $event.target.value)" :value="transaction.amount || 0" class="input mb-0 w-max" name="amount" id="transaction_amount">
                    @else
                    <div class="font-medium" x-text="'₹ ' + transaction.amount || 0"></div>
                    @endif
                </div>

                <div x-show="data.user.user_type != 'student' || transaction.remarks > 0" class="flex justify-between items-center">
                    <div>Return</div>
                    @if (!$user->is('student'))
                        <input type="number" @change="updateTransaction('remarks', $event.target.value)" :value="transaction.remarks || 0" class="input mb-0 w-max" name="remarks" id="transaction_remarks">
                    @else
                    <div class="font-medium" x-text="'₹ ' + transaction.remarks || 0"></div>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <div>Time</div>
                    @if ($user->is('admin'))
                        <input type="datetime-local" @change="updateTransaction('created_at', $event.target.value)" :value="dateTimeLocal(transaction.created_at)" class="input mb-0 w-max" name="created_at" id="created_at">
                    @else
                    <div class="font-medium" x-text="dateData(transaction.created_at)"></div>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <div>Category</div>
                    @if (!$user->is('student'))
                        <select @change="updateTransaction('category_id', $event.target.value)" class="input mb-0 w-max" name="category_id">
                            <option value="">Select Category</option>
                                <template x-for="category in data.categories">
                                    <option :value="category.id" :selected="category.id == transaction.category_id" x-text="category.name"></option>
                                </template>
                        </select>
                    @else
                    <div class="font-medium capitalize" x-text="transaction.category ? transaction.category : ''"></div>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <div>Description</div>
                    @if (!$user->is('student'))
                        <textarea @change="updateTransaction('description', $event.target.value)" name="description" class="input w-max mb-0" cols="30" rows="3" :value="transaction.description"></textarea>
                    @else
                    <div class="font-medium" x-text="transaction.description"></div>
                    @endif
                </div>
            </div>

          </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            @if(!$user->is('student'))
            <button :disabled="loading" :class="{ 'hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-400': !loading, 'opacity-40': loading }" x-text="loading ? 'Loading...' : 'Save Changes'" @click.prevent="submit($event.target.closest('.modal').querySelector('.form-container form'))" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2  text-base font-medium text-white bg-blue-400 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save Changes</button>
            @endif
            <button @click="close()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/fuse.js') }}"></script>
  <script src="{{ asset('js/asset.js') }}"></script>
  <script type="text/javascript">

    function modal() {
        return {
            dateData(date) {
                var date = new Date(date);
                return date.getDate() + '-' + this.month(date.getMonth()) + '-' + date.getFullYear() + ' ' + this.timeTo12HoursFormat(date);
            },
            // inject viewable function from app() to this object
            viewable(transaction) {
                var self = this;
                var sender = transaction.sender ?? this.data.user ?? {};
                var reciever = transaction.reciever ?? this.data.user ?? {};
                var sender_is_student = sender.user_type == 'student';
                var reciever_is_student = reciever.user_type == 'student';
                var user_is_student = this.data.user.user_type == 'student';
                var type = (this.data.user.id == sender.id && !user_is_student) || (this.data.user.id == reciever.id && user_is_student) ? 'expense' : 'deposit';
                return {
                    id: transaction.id,
                    ogAmount: transaction.amount,
                    amount(){
                        return (self.transaction_updates.amount ?? transaction.amount ?? 0) - (self.transaction_updates.remarks ?? transaction.remarks ?? 0);
                    },
                    category: transaction.category ? transaction.category : '',
                    category_id: transaction.category_id,
                    description: transaction.description ? transaction.description : '',
                    sender: (sender_is_student ? sender.username + ' ' : '') + sender.name,
                    reciever: (reciever_is_student ? reciever.username + ' ' : '') + reciever.name,
                    type: type,
                    remarks: transaction.remarks ? transaction.remarks : '',
                    class: ((sender.meta ?? reciever.meta ?? {}).class ?? {}).name,
                };
            },
            dateTimeLocal(date) {
                if (!date) return '';
                var date = new Date(date);
                date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
                return date.toISOString().slice(0,16);
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
                transactions: [],
                categories: []
            },
            dataAdded: false,
            transaction: {
                reciever: {},
                sender: {}
            },
            transaction_updates: {},
            show(data) {
                if (!this.dataAdded) {
                    this.data = JSON.parse(JSON.stringify(data.data));
                    this.dataAdded = true;
                }
                this.transaction = data.transaction;
                this.transaction_updates = {
                    amount: this.transaction.amount
                };
                this.open = true;
            },
            updateTransaction(key, value) {
                if (this.transaction[key] != value) {
                    this.transaction_updates[key] = value;
                } else if(key != 'amount') {
                    delete this.transaction_updates[key];
                }
            },
            close() {
                this.open = false;
            },
            submit(form) {
                if (!Object.keys(this.transaction_updates).length || (Object.keys(this.transaction_updates).length <= 1 && this.transaction.amount == this.transaction_updates.amount)) {
                    this.$dispatch('alpine-show-message', {
                        type: 'error',
                        data: 'No Changes Made',
                    });
                    return false;
                }
                if (this.loading) return false;
                this.loading = true;
                var data = new FormData();
                data.append('_token', '{{ csrf_token() }}');
                data.append('id', this.transaction.id);
                for (var key in this.transaction_updates) {
                    data.append(key, this.transaction_updates[key]);
                }

                fetch(form.action, {
                    body: data,
                    method: form.method || "POST",
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
                            data: 'Transaction Edited Successfully!',
                        });
                        this.close();
                        this.$dispatch('refresh-transactions');
                        return false;
                    }
                    if (json.errors) {
                        Object.keys(json.errors || {}).filter(o => o).forEach(key => {
                            this.$dispatch('alpine-show-message', {
                                type: 'error',
                                data: json.errors[key][0],
                            });
                        });
                    } else {
                        this.$dispatch('alpine-show-message', {
                            type: 'error',
                            data: 'Something Went Wrong! Please Double Check Your Inputs',
                        });
                    }
                }).catch(e => {
                    this.$dispatch('alpine-show-message', {
                        type: 'error',
                        data: 'Something Went Wrong! Please Double Check Your Inputs',
                    });
                    this.loading = false;
                });
            }
        }
    }

    function app() {
      return {
        init() {
            this.loadTransactions();
            @if(!$user->is('student'))
            this.$watch('student_settings.class_id', (class_id) => {
                this.advanced_fields.class_id = class_id;
                this.advanced_fields.class_text = 'All ' + ((this.classes.find(o => o.id == class_id) || {}).name || '') + ' Students';
                if (!class_id) this.advanced_fields.class_text += ' from the College';
            });
            @endif
        },
        lastTransactionDetails: {
            loading: false,
        },
        firstLoading: true,
        transaction_settings: {
            user_id: true,
        },
        show_advanced: false,
        advanced_amount: 0,
        advanced_applicable_amount() {
            var amount;
            if (!this.advanced_fields.divide || !(amount = this.advanced_amount)) return 0;
            var students_count = this.advanced_fields.class_id ? this.classes.find(c => c.id == this.advanced_fields.class_id).students_count : this.classes.reduce((sum, class_) => { return sum + (class_.students_count ?? 0);}, 0);
            var tagged_students = this.tags.map(tag => this.allStudents.find(s => s.id == tag)).filter(o => (o && o.meta && o.meta.class && o.meta.class.id == this.advanced_fields.class_id) || !this.advanced_fields.class_id);
            students_count = students_count - tagged_students.length;
            if (!this.advanced_fields.exclude) students_count = tagged_students.length;
            if (!students_count) return 0;
            return `${amount}/${students_count} = ` + amount / students_count;
        },
        advanced_total_amount() {
            var amount;
            if (!(amount = this.advanced_amount)) return 0;
            var students_count = this.advanced_fields.class_id ? this.classes.find(c => c.id == this.advanced_fields.class_id).students_count : this.classes.reduce((sum, class_) => { return sum + (class_.students_count ?? 0);}, 0);
            var tagged_students = this.tags.map(tag => this.allStudents.find(s => s.id == tag)).filter(o => (o && o.meta && o.meta.class && o.meta.class.id == this.advanced_fields.class_id) || !this.advanced_fields.class_id);
            students_count = students_count - tagged_students.length;
            if (!this.advanced_fields.exclude) students_count = tagged_students.length;
            return amount * students_count;
        },
        advanced_fields: {
            exclude: null,
            class_id: null,
            divide: null,
            class_text: 'All Students from the College',
        },
        viewable(transaction) {
            var sender = transaction.sender ?? this.user ?? {};
            var reciever = transaction.reciever ?? this.user ?? {};
            var sender_is_student = sender.user_type == 'student';
            var reciever_is_student = reciever.user_type == 'student';
            var user_is_student = this.user.user_type == 'student';
            var type = (this.user.id == sender.id && !user_is_student) || (this.user.id == reciever.id && user_is_student) ? 'expense' : 'deposit';
            return {
                id: transaction.id,
                ogAmount: transaction.amount,
                amount(){
                    return (transaction.amount ?? 0) - (transaction.remarks ?? 0);
                },
                category: transaction.category ? transaction.category : '',
                category_id: transaction.category_id,
                description: transaction.description ? transaction.description : '',
                sender: (sender_is_student ? sender.username + ' ' : '') + sender.name,
                reciever: (reciever_is_student ? reciever.username + ' ' : '') + reciever.name,
                type: type,
                remarks: transaction.remarks ? transaction.remarks : '',
            };
        },
        filters_show: true,
        filters_show2: true,
        @if(!$user->is('student'))
            classes: {!! $classes->toJson() !!},
        @endif
        student_settings: {},
        changeStudentsSetting(item, new_value, toggle = false, load = true) {
            if (this.student_settings[item] == new_value) {
                if (!toggle) return false;
                new_value = false;
            }
            if (new_value && new_value != '') {
                this.student_settings[item] = new_value;
            } else {
                delete this.student_settings[item];
            }
            if (!load) return false;
            this.searchedItems = [];
            this.searchStudents();
        },
        changeTransactionSetting(item, new_value, toggle = false, load = true) {
            if (this.transaction_settings[item] == new_value) {
                if (!toggle) return false;
                new_value = false;
            }
            if (new_value && new_value != '') {
                this.transaction_settings[item] = new_value;
            } else {
                delete this.transaction_settings[item];
            }
            if (!load) return false;
            this.firstLoading = true;
            this.loadTransactions();
        },
        loadTransactions(more = false, url = '{{ route('ajax') }}') {
            this.lastTransactionDetails.loading = true;
            if (this.tr_abortController) {
                this.tr_abortController.abort();
            }
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            self = this;
            formData.append('data', JSON.stringify({
                action: 'transactions',
                inputs: self.transaction_settings
            }));
            this.tr_abortController = new AbortController();
            let data = fetch(url, {
                method: 'POST',
                signal: this.tr_abortController.signal,
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            }).then(json => {
                return json.json();
            }).then(data => {
                if (!data.success) {
                    if (this.retries >= 3) {
                        this.$dispatch('alpine-show-message', {
                            type: 'error',
                            data: 'Error Loading Transactions. Please refresh and try again!',
                        });
                        this.lastTransactionDetails.loading = false;
                        this.firstLoading = false;
                        return;
                    }
                    this.retries++;
                    this.loadTransactions(more, url);
                    return false;
                }
                console.log(data);
                this.retries = 0;
                if (!more)
                    this.transactions = data.transactions.data;
                else
                    this.transactions = this.transactions.concat(data.transactions.data);
                this.lastTransactionDetails = {...data, loading: false};
                this.firstLoading = false;
            }).catch(e => {
                if (e instanceof DOMException && e.name === 'AbortError') {
                    this.lastTransactionDetails.loading = false;
                    this.firstLoading = false;
                    return;
                }
                if (this.retries >= 3) {
                    this.$dispatch('alpine-show-message', {
                        type: 'error',
                        data: 'Error Loading Transactions. Please refresh and try again!',
                    });
                    this.lastTransactionDetails.loading = false;
                    this.firstLoading = false;
                    return;
                }
                this.retries++;
                this.loadTransactions(more, url);
                return false;
            });
        },
        retries: 0,
        abortController: false,
        tr_abortController: false,
        searchStudents(url = '{{ route('ajax') }}') {
            this.loading = false;
            var searched;
            this.student = {
                meta: {
                    ad_no: '',
                    class: {}
                }
            };
            this.students = [];
            if (this.trimAndLowerCaseIfString(this.ad_no) == '') {
                return false;
            }
            this.loading = true;
            if (this.abortController) {
                this.abortController.abort();
            }
            if (searched = this.searchedItems.find(item => item.keyword == this.ad_no)) {
                this.students = searched.result;
                this.loading = false;
                // this.student = this.getStudent();
                return;
            }
            this.abortController = false;
            this.abortController = new AbortController();
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            var self = this;
            formData.append('data', JSON.stringify({
                action: 'students',
                inputs: {
                    search: this.ad_no,
                    limit: 5,
                    ...self.student_settings
                }
            }));
            let data = fetch(url, {
                method: 'POST',
                signal: this.abortController.signal,
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            }).then(json => {
                return json.json();
            }).then(data => {
                this.students = data.students;
                if (data.success) {
                    this.searchedItems.push({
                        keyword: this.ad_no,
                        result: data.students
                    });
                    this.allStudents = this.addWithoutDuplicates(this.allStudents, this.students, 'username');
                } else {
                    this.$dispatch('alpine-show-message', {
                        type: 'error',
                        data: 'Error Loading Students. Please try again!',
                    });
                }
                this.loading = false;
                // this.student = this.getStudent();
            }).catch(err => {
                this.loading = false;
                if (err instanceof DOMException && err.name === 'AbortError') {
                    return;
                }
                this.$dispatch('alpine-show-message', {
                    type: 'error',
                    data: 'Error Loading Students. Please try again!',
                });
            });
        },
        abortController2: false,
        ad_no2: '',
        loading3: false,
        students2: [],
        student2: {
            meta: {
                ad_no: '',
                class: {}
            }
        },
        filters_show3: true,
        student_settings2: {},
        changeStudentsSetting2(item, new_value, toggle = false, load = true) {
            if (this.student_settings2[item] == new_value) {
                if (!toggle) return false;
                new_value = false;
            }
            if (new_value && new_value != '') {
                this.student_settings2[item] = new_value;
            } else {
                delete this.student_settings2[item];
            }
            if (!load) return false;
            this.searchedItems = [];
            this.searchStudents2();
            this.loadTransactions();
        },
        searchStudents2(url = '{{ route('ajax') }}') {
            this.loading3 = false;
            var searched;
            this.student2 = {
                meta: {
                    ad_no: '',
                    class: {}
                }
            };
            this.students2 = [];
            if (this.trimAndLowerCaseIfString(this.ad_no2) == '') {
                return false;
            }
            this.loading3 = true;
            if (this.abortController2) {
                this.abortController2.abort();
            }
            if (searched = this.searchedItems.find(item => item.keyword == this.ad_no2)) {
                this.students2 = searched.result;
                this.loading3 = false;
                // this.student = this.getStudent();
                return;
            }
            this.abortController2 = false;
            this.abortController2 = new AbortController();
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            var self = this;
            formData.append('data', JSON.stringify({
                action: 'students',
                inputs: {
                    search: this.ad_no2,
                    limit: 5,
                    ...self.student_settings2
                }
            }));
            let data = fetch(url, {
                method: 'POST',
                signal: this.abortController2.signal,
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            }).then(json => {
                return json.json();
            }).then(data => {
                this.students2 = data.students;
                if (data.success) {
                    this.searchedItems.push({
                        keyword: this.ad_no2,
                        result: data.students
                    });
                    this.allStudents = this.addWithoutDuplicates(this.allStudents, this.students2, 'username');
                } else {
                    this.$dispatch('alpine-show-message', {
                        type: 'error',
                        data: 'Error Loading Students. Please try again!',
                    });
                }
                this.loading3 = false;
                // this.student = this.getStudent();
            }).catch(err => {
                this.loading3 = false;
                if (err instanceof DOMException && err.name === 'AbortError') {
                    return;
                }
                this.$dispatch('alpine-show-message', {
                    type: 'error',
                    data: 'Error Loading Students. Please try again!',
                });
            });
        },
        addWithoutDuplicates(array, newArray, array_key, newArray_key = null) {
            newArray_key = newArray_key ?? array_key;
            newArray.forEach(item => {
                if (!array.find(item2 => item2[array_key] == item[newArray_key])) {
                    array.push(item);
                }
            });
            return array;
        },
        allStudents: [],
        searchedItems: [],
        students: [],
        showTransaction(transaction) {
            this.$dispatch('view-transaction',
                {
                    data: {
                        transactions: this.transactions,
                        students: this.students,
                        user: this.user,
                        categories: this.categories,
                    },
                    transaction: transaction
                }
            );
        },
        hasLoadMore() {
            return this.lastTransactionDetails.transactions && this.lastTransactionDetails.transactions.next_page_url;
        },
        loadMore() {
            this.loadTransactions(true, this.lastTransactionDetails.transactions.next_page_url);
        },
        transactions: [],
        user: {!! $user->toJson() !!},
        categories: {!! $categories->toJson() !!},
        category_new: false,
        student: {
            meta: {
              ad_no: '',
              class: {}
            },
        },
        category: {},
        userDetails() {
            return this.transactions.reduce((acc, curr) => {
                if (curr.transaction_type == 'expense') {
                    acc.deposit += curr.amount - curr.remarks ?? 0;
                    acc.balance += curr.amount - curr.remarks ?? 0;
                } else {
                    acc.expense += curr.amount - curr.remarks ?? 0;
                    acc.balance -= curr.amount - curr.remarks ?? 0;
                }
                return acc;
            }, {
                deposit: 0,
                expense: 0,
                balance: 0
            });
        },
        stop: false,
        stopCategorySearch: false,
        ad_no: '',
        category_id: '',
        category_name: '',
        loading: false,
        transaction_type: false,
        getStudent(id) {
            return this.allStudents.find(student => student.id == id);
        },
        getCategory() {
            return this.categories.find(category => this.trimAndLowerCaseIfString(category.name) == this.trimAndLowerCaseIfString(this.category_name)) || {
                name: '',
                id: ''
            };
        },
        searchResults() {
            var data = [];
            if (this.ad_no != '' && (!isNaN(this.ad_no) || this.ad_no.trim() != '')) {
                    const options = {
                    shouldSort: true,
                    keys: ['name', 'username', 'class.name'],
                    threshold: 0.5,
                    findAllMatches: true,
                };
                const fuse = new Fuse(this.students, options);
                data = fuse.search(this.ad_no).map(result => result.item);

            }
            return data;
        },
        trimAndLowerCaseIfString(value) {
            if (typeof value == 'string') {
                return value.trim().toLowerCase();
            }
            return value;
        },
        focused: false,
        categoryResults() {
            var data = this.categories;
            if (this.category_name != '' && (!isNaN(this.category_name) || this.category_name.trim() != '')) {
                    const options = {
                    shouldSort: true,
                    keys: ['name', 'slug'],
                    threshold: 0.1,
                };
                const fuse = new Fuse(this.categories, options);
                data = fuse.search(this.category_name).map(result => result.item);

                @if($user->isAdmin())
                if (typeof this.trimAndLowerCaseIfString(this.category_name) == 'string' && this.trimAndLowerCaseIfString(this.category_name).length > 3 && !data.find(category => this.trimAndLowerCaseIfString(category.name) == this.trimAndLowerCaseIfString(this.category_name))) {
                    data.push({
                        name: this.category_name,
                        id: 0,
                        new: 'new'
                    });
                }
                @endif

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
        addedStudents() {
            return this.allStudents.filter(student => this.tags.includes(student.id));
        },
        tags: [],
        removeTag(i) {
            this.tags.splice(i, 1);
        },
        inTag(id) {
            return this.tags.includes(id);
        },
        addTag(id) {
            var student;
            // this.ad_no = this.students[i].username; this.stop = true;
            if ((student = this.getStudent(id)) && !this.inTag(id)) {
                this.tags.push(student.id);
                this.ad_no = '';
                this.stop = true;
            }
            return false;
        },
        submit(form) {
          if (!this.tags.length && (!this.show_advanced || !this.advanced_fields.exclude)) {
              this.$dispatch('alpine-show-message', {
                type: 'error',
                data: 'Please Select User to Transact',
              });
              return false;
          }
          if (this.loading) return false;
          var data = new FormData(form);
          data.append('ids', this.tags.join(','));
          if (this.show_advanced) {
            if (this.advanced_fields.exclude) {
                if (!confirm('You are about to make bulk transactions with ' + this.advanced_fields.class_text + '. Are you sure to continue?' ))
                    return false;
                data.append('exclude', 1);
                if (this.advanced_fields.class_id)
                    data.append('class_id', this.advanced_fields.class_id);
            }
            if (this.advanced_fields.divide)
                data.append('divide', 1);
          }
          this.loading = true;
          console.log('continued');
          this.reset();
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
                if (json.category) {
                    this.categories.push(json.category);
                    this.category_name = json.category.name;
                    this.category_new = false;
                    this.category = this.getCategory();
                    this.category_id = this.getCategory().id;
                }
                this.$dispatch('alpine-show-message', {
                    type: 'success',
                    data: 'Transaction Success!',
                });
                this.$dispatch('refresh-transactions');
                this.students = [];
                this.searchedItems = [];
                this.tags = [];
                this.ad_no = '';
                // this.stop = false;
                // this.show_advanced = false;
                // this.advanced_fields = {
                //     class_id: '',
                //     class_text: '',
                //     exclude: false,
                //     divide: false,
                // };
                form.querySelector('input[name="ad_no"]').value = '';
                form.querySelector('input[name="amount"]').value = '';
                form.querySelector('[name="description"]').value = '';
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
