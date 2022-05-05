<nav class="sm:col-span-2 hidden sm:block h-screen overflow-y-auto sticky top-0">
    <div class="bg-gray-800 sm:flex flex-col justify-start rounded-none h-full">
        <div class="px-8 py-2">
            <div class="my-4 flex items-center justify-center">
                <div class="text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                    </svg>
                </div>
                <div class="text-gray-600 font-black rounded-full bg-yellow-400" style="padding: 2px 7px; transform: rotate(-30deg) translate(-20px, -17px); transform-origin: top; font-size: 0.75rem;">
                    ₹
                </div>
                <div class="text-2xl text-gray-200 ml-0 -translate-x-2 mr-auto font-semibold">
                    dashboard
                </div>
            </div>
        </div>
        <div class="px-4 py-4">
            <ul class="list-none w-full">
                <li class="my-2">
                    <a href="{{ route('users', ['type' => 'teachers']) }}" class="nav-link {{ route('users', ['type' => 'teachers']) == url()->current() ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-4">Teachers</span>
                    </a>
                </li>
                <li class="my-2">
                    <a href="{{ route('users', ['type' => 'students']) }}" class="nav-link {{ route('users', ['type' => 'students']) == url()->current() ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-4">Students</span>
                    </a>
                </li>
                <li class="my-2">
                    <a href="{{ route('users', ['type' => 'parents']) }}" class="nav-link {{ route('users', ['type' => 'parents']) == url()->current() ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="ml-4">Parents</span>
                    </a>
                </li>
                @if(\Route::has('shops'))
                <li class="my-2">
                    <a href="{{ route('shops') }}" class="nav-link {{ request()->routeIs('shops') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="ml-4">Shops</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <div class="px-4 py-4 grow flex items-end justify-center">
            <div class="text-gray-400 text-xs font-normal text-center">
                Copyright &copy; {{ date('Y') }} <br> Dashboard | Darul Hasanath
            </div>
        </div>
    </div>
</nav>
