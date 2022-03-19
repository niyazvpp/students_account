<nav class="sm:col-span-2 min-h-screen">
    <div class="card rounded-none h-full border-r">
        <div class="px-8 py-4">
            <div class="my-4 flex items-center justify-center">
                <div class="text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                    </svg>
                </div>
                <div class="text-gray-600 font-black rounded-full bg-yellow-400" style="padding: 2px 7px; transform: rotate(-30deg) translate(-20px, -17px); transform-origin: top; font-size: 0.75rem;">
                    ₹
                </div>
                <div class="text-2xl text-gray-700 ml-0 -translate-x-2 mr-auto font-semibold">
                    dashboard
                </div>
            </div>
        </div>
        <div class="px-4 py-4">
            <ul class="list-none w-full">
                <li class="my-2">
                    <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ml-4">Users</span>
                    </a>
                </li>
                <li class="my-2">
                    <a href="{{ route('shops') }}" class="nav-link {{ request()->routeIs('shops') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="ml-4">Shops</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>