@extends('layouts.dash')

@section('main')
<div class="" x-data>

    <div class="py-6 flex sm:justify-end justify-center items-center">
        <button @click="$dispatch('adduser', 0)" type="button" class="btn btn-yellow px-8 py-1.5 my-auto w-max">New {{ ucfirst($type) }}</button>
    </div>

   <div class="w-full">

        @php


        $tr_attributes = '@click="$dispatch(\'view-transaction\', transaction)"';
        $headers = array_merge(['Id', 'Name', 'Username', 'Email', 'Mobile', 'Old Balance'], $type == 'teacher' ? ['Class'] : [], ['Edit']);
        $body = [];
        foreach ($users as $count => $user) {
            $body[] = array_merge([
                $count * 1 + 1,
                $user->name,
                $user->username,
                $user->email,
                $user->mobile,
                $user->old_balance,
            ], $type == 'teacher' ? [($user->class ? $user->class->name : '')] : [],['<div class="inline-flex justify-center"><button @click="$dispatch(\'adduser\', ' . $user->id . ')" class="btn text-gray-600 bg-gray-100 border border-gray-200 shadow-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button> <a href="' . route('teacher', ['teacher' => $user->id]) . '" class="btn ml-2 text-gray-600 bg-gray-100 border border-gray-200 shadow-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a></div>']);
        }

        @endphp

        <div class="card">
            <div class="card-body overflow-x-auto">
                <x-table :headers="$headers" :tr_attributes="$tr_attributes" :body="$body" />
            </div>
        </div>

   </div>
</div>

<div x-data='modal()'
x-show="open" @adduser.window="show($event.detail)" class="fixed z-10 inset-0 overflow-y-auto modal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
          <form @submit.prevent="submit($event.target)" action="{{ route('users.edit', ['type' => $type . 's']) }}" method="POST">
            @csrf
            <input type="hidden" name="user" id="user" :value="{{ $type }}.id">
            <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900" x-text="add ? 'Add New {{ ucfirst($type) }}' : 'Edit {{ ucfirst($type) }}'">
              Add New {{ ucfirst($type) }}
            </h3>

            <div class="grid grid-cols-3">
              <div class="form-group col-span-2">
                <label for="name" class="label">Name:</label>
                <input name="name" :value="{{ $type }}.name" id="name" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>
              <div class="form-group">
                <label for="old_balance" class="label">Old Balance:</label>
                <input name="old_balance" :value="{{ $type }}.old_balance" id="old_balance" type="number" value="0" class="input">
                <small class="text-red-500 error"></small>
              </div>
            </div>

            @if($type == 'teacher')

              <div class="form-group col-span-2">
                <label for="class" class="label">Assign Class</label>
                <select name="class" :value="teacher.class ? teacher.class.id : ''" id="class" type="text" class="input">
                  <option value=""> --- Not Now --- </option>
                  <template x-for="classe in classes">
                      <option :value="classe.id" x-text="classe.name"></option>
                  </template>

                </select>
                <small class="text-red-500 error"></small>
              </div>

            @endif

            <div class="sm:grid sm:grid-cols-2">
              <div class="form-group">
                <label for="email" class="label">Email:</label>
                <input name="email" :value="{{ $type }}.email" id="email" type="email" class="input">
                <small class="text-red-500 error"></small>
              </div>
              <div class="form-group">
                <label for="mobile" class="label">Mobile:</label>
                <input name="mobile" :value="{{ $type }}.mobile" id="mobile" type="number" class="input">
                <small class="text-red-500 error"></small>
              </div>
            </div>

            <div class="sm:grid sm:grid-cols-2">
              <div class="form-group">
                <label for="username" class="label">Username:</label>
                <input name="username" :value="{{ $type }}.username" id="username" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>
              <div class="form-group">
                <label for="password" class="label">Password:</label>
                <input name="password" :value="{{ $type }}.password" id="password" type="password" class="input">
                <small class="text-red-500 error"></small>
              </div>
            </div>
          </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button :disabled="loading" :class="{ 'hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-400': !loading, 'opacity-40': loading }" x-text="loading ? 'Loading...' : add ? 'Add {{ ucfirst($type) }}' : 'Save Changes'" @click.prevent="submit($event.target.closest('.modal').querySelector('.form-container form'))" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2  text-base font-medium text-white bg-blue-400 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Add {{ ucfirst($type) }}</button>
          <button @click="close()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">

    function modal() {
      return {
    init() {
          this.$watch('open', val => {
            if (val) {
              this.$nextTick(()=> {
                console.log(document.querySelector('[x-data="modal()"]').querySelector('input.input,select.input,textarea.input'));
                document.querySelector('[x-data="modal()"]').querySelector('input.input,select.input,textarea.input').focus();
              });
            }
          });
        },
    classes: {!! $classes->toJson() !!},
    open: false,
    loading: false,
    add: true,
    {{ $type }}s: {!! $usersData->toJson() !!},
    {{ $type }}: {
      id: 0,
      old_balance: 0,
      class: {}
    },
    show(id = 0) {
      @if($type == 'parent')
      if (!id) {
        this.$dispatch('alpine-show-message', {
          data: 'Parents Could Not be created manually, they are auto generated! You are free to add more than 1 student to an existing parent!',
          type: 'error'
        });
        return false;
      }
      @endif
      this.open = true;
      var filtered = this.{{ $type }}s.filter({{ $type }} => {{ $type }}.id == id);
      this.{{ $type }} = filtered.length ? filtered[0] : {
        id: 0,
        old_balance: 0,
        class: {}
      };
      this.add = !filtered.length;
    },
    close() {
      this.reset();
      this.open = false;
    },
    reset() {
      document.querySelectorAll(".form-group").forEach(formGroup => {
        formGroup.classList.remove("validated");
      });
    },
    submit(form) {
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
        {{-- console.log(json); --}}
        this.loading = false;
        if (json.status == "success") {
          window.location = "";
          return false;
        }
        if (json.errors) {
          Object.keys(json.errors).forEach(name => {
            var obj = document.querySelector("[name=" + name +"]");
            var error = json.errors[name][0];
            obj.closest(".form-group").classList.add("validated");
            obj.closest(".form-group").querySelector(".error").innerHTML = error;
          });
        }
      }).catch(e => {
        this.loading = false;
      });
    }
};
    }
  </script>

@endsection
