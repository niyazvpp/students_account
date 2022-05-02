@extends('layouts.dash')

@section('main')
<div class="px-8 py-4 my-6" x-data>

    <div class="py-6 flex justify-end items-center">
        <button @click="$dispatch('adduser', 0)" type="button" class="btn btn-blue px-8 py-1.5 my-auto w-max">New Class</button>
    </div>

   <div class="w-full">

        @php

        $headers = ['Id', 'Name', 'FullName', 'Teacher', 'Edit'];
        $body = [];
        foreach ($classes as $count => $class) {
            $body[] = [
                $count * 1 + 1,
                $class->name,
                $class->fullname,
                $class->teacher ? $class->teacher->name : '',
                '<button @click="$dispatch(\'adduser\', ' . $class->id . ')" class="btn text-gray-600 bg-gray-100 border border-gray-200 shadow-none">Edit</button>'
            ];
        }

        @endphp

        <div class="card">
            <div class="card-body overflow-x-auto">
                <x-table :headers="$headers" :body="$body" />
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
          <form @submit.prevent="submit($event.target)" action="{{ route('classes.edit') }}" method="POST">
            @csrf
            <input type="hidden" name="class" id="class" :value="currentClass.id">
            <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900" x-text="add ? 'Add New Class' : 'Edit Class'">
              Add New Class
            </h3>
            
              <div class="form-group">
                <label for="name" class="label">Name:</label>
                <input name="name" :value="currentClass.name" id="name" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>

              <div class="form-group">
                <label for="fullname" class="label">FullName:</label>
                <input name="fullname" :value="currentClass.fullname" id="fullname" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>

            <div class="form-group">
              <label for="class" class="label">Teacher</label>
              <select name="teacher_id" :value="currentClass.teacher ? currentClass.teacher.id : ''" id="class" type="text" class="input">
                <option value=""> --- Not Now --- </option>
                <template x-for="teacher in teachers">
                    <option :value="teacher.id" x-text="teacher.name"></option>
                </template>

              </select>
              <small class="text-red-500 error"></small>
            </div>

          </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button :disabled="loading" :class="{ 'hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-400': !loading, 'opacity-40': loading }" x-text="loading ? 'Loading...' : add ? 'Add Class' : 'Save Changes'" @click.prevent="submit($event.target.closest('.modal').querySelector('.form-container form'))" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2  text-base font-medium text-white bg-blue-400 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Add Class</button>
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
        teachers: {!! $teachers->toJson() !!},
        open: false,
        loading: false,
        add: true,
        currentClass: {
          id: 0,
          teacher: {}
        },
        show(id = 0) {
          this.open = true;
          var filtered = this.classes.filter(classe => classe.id == id);
          this.currentClass = filtered.length ? filtered[0] : {
            id: 0,
            teacher: {}
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
            console.log(e);
            this.loading = false;
          });
        }
    }
    }
  </script>

@endsection