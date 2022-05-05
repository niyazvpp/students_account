@extends('layouts.dash')

@section('main')
<div class="" x-data>

    <div class="py-6 flex sm:justify-end justify-center items-center">
        <button @click="$dispatch('adduser', 0)" type="button" class="btn btn-yellow px-8 py-1.5 my-auto w-max">New Parent</button>
    </div>

   <div class="w-full">

        @php

        $headers = ['Id', 'Name', 'Username', 'Students', 'Edit'];
        $body = [];
        foreach ($users as $count => $user) {
          $students_list = '';
          foreach($user->students ?? [] as $student) {
            $students_list .= '<div>' . $student->ad_no . ' - ' . ucfirst($student->user->name) . ' - ' .  $student->class->name . '</div>';
          }
            $body[] = [
                $count * 1 + 1,
                $user->name,
                $user->username,
                $students_list,
            '<button @click="$dispatch(\'adduser\', ' . $user->id . ')" class="btn text-gray-600 bg-gray-100 border border-gray-200 shadow-none">Edit</button>'];
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
          <form @submit.prevent="submit($event.target)" action="{{ route('parents.edit') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="user" :value="parent.id">
            <input type="hidden" name="ids" id="ids" :value="ids_list()">
            <h3 class="text-lg leading-6 mb-4 font-semibold text-gray-900" x-text="add ? 'Add New Parent' : 'Edit Parent'">
              Add New Parent
            </h3>

            <div class="grid grid-cols-3">
              <div class="form-group col-span-2">
                <label for="name" class="label">Name:</label>
                <input name="name" disabled :value="parent.name" id="name" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>
            </div>


              <div class="form-group col-span-2 relative">
                <label for="class" class="label">Students</label>
                <input type="search" name="searchstudents" placeholder="Search Students Name / Admission No" x-model="searchStudent" class="input">
                <div x-show="searchStudent.trim().length" class="shadow-lg py-1 bg-white absolute border right-3 left-0 rounded-lg">
                  <ul class="overflow-y-auto" style="max-height: 100px;">
                    <template x-for="result in searchResults()">
                      <li @click="addTag(result.user.id)" class="border-b py-1 cursor-pointer px-2 py-1 hover:bg-gray-50 text-sm" x-text="result.ad_no + ' - ' + result.user.name + ' - ' + result.class.name"></li>
                    </template>
                    <template x-if="!searchResults().length">
                      <li class="px-2 py-1 text-sm" x-text="'No Result Found!'"></li>
                    </template>
                  </ul>
                </div>
                <template x-for="(tag, index) in addedStudents()" :key="tag.id">
                  <div class="bg-indigo-100 inline-flex items-center text-sm rounded mt-2 mr-1">
                    <span class="ml-2 mr-1 leading-relaxed truncate max-w-xs" x-text="tag.ad_no + ' - ' + tag.user.name + ' - ' + tag.class.name"></span>
                    <button type="button" @click.prevent="removeTag(index)" class="w-6 h-8 inline-block align-middle text-gray-500 hover:text-gray-600 focus:outline-none">
                      <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
                    </button>
                  </div>
                </template>
                <small class="text-red-500 error"></small>
              </div>

            <div class="sm:grid sm:grid-cols-2">
              <div class="form-group">
                <label for="username" class="label">Username:</label>
                <input name="username" disabled :value="parent.username" id="username" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>
              <div class="form-group">
                <label for="password" class="label">Password:</label>
                <input disabled name="password" :value="parent.students[0].parent_password" id="password" type="text" class="input">
                <small class="text-red-500 error"></small>
              </div>
            </div>
          </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button :disabled="loading" :class="{ 'hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-400': !loading, 'opacity-40': loading }" x-text="loading ? 'Loading...' : add ? 'Add Parent' : 'Save Changes'" @click.prevent="submit($event.target.closest('.modal').querySelector('.form-container form'))" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2  text-base font-medium text-white bg-blue-400 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Add Parent</button>
          <button @click="close()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fuse.js@6.5.3"></script>
  <script type="text/javascript">
    function modal() {
      return {
    init() {
      this.$watch('open', val => {
        if (val) {
          this.$nextTick(()=> {
            document.querySelector('[x-data="modal()"]').querySelector('input.input:not([disabled]),select.input:not([disabled]),textarea.input:not([disabled])').focus();
          });
        }
      });
    },
    ids_list() {
      console.log(this.tags.join(','));
      return this.tags.join(',');
    },
    tags: [],
    removeTag(i) {
       if (!this.parent.students.map(student => student.user.id).includes(this.tags[i])) {
        this.tags.splice(i, 1);
        return false;
       }
       this.$dispatch('alpine-show-message', {
          data: 'Removing existing must cause student being without parent! Alternately, You can add this student to another parent to remove him from here!',
          type: 'error'
        });
    },
    addTag(id) {
      this.searchStudent = '';
      this.tags.push(id);
    },
    addedStudents() {
      return this.students.filter(student => this.tags.includes(student.user.id));
    },
    students: {!! $students->toJson() !!},
    searchStudent: '',
    open: false,
    loading: false,
    add: true,
    parents: {!! $usersData->toJson() !!},
    parent: {
      id: 0,
      students: [{user: {}}]
    },
    searchResults() {
      var data = [];
      if (this.searchStudent.trim().length) {
            const options = {
              shouldSort: true,
              keys: ['user.name', 'ad_no', 'class.name'],
              threshold: 0
          };
          const fuse = new Fuse(this.students.filter(student => !this.tags.includes(student.user.id)), options);
          data = fuse.search(this.searchStudent.trim()).map(result => result.item);
      }
      return data;
    },
    show(id = 0) {
      this.searchStudent = '';
      if (!id) {
        this.$dispatch('alpine-show-message', {
          data: 'Parents Could Not be created manually, they are auto generated! You are free to add more than 1 student to an existing parent!',
          type: 'error'
        });
        return false;
      }
      this.open = true;
      var filtered = this.parents.filter(parent => parent.id == id);
      this.parent = filtered.length ? filtered[0] : {
        id: 0,
        students: [{user: {}}]
      };
      this.tags = this.parent.students.map(student => student.user.id);
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
