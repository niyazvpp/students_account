<div class="flex justify-start bg-gradient-to-r from-cyan-500 to-blue-500 shadow">
    <div class="sm:hidden pl-4 py-2 flex items-center justify-center">
        <div class="text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" stroke="white" viewBox="0 0 20 20" fill="currentColor">
            <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
            </svg>
        </div>
        <div class="text-gray-600 font-black rounded-full bg-yellow-400" style="padding: 1.25px 5px; transform: rotate(-30deg) translate(-18px, -16px); transform-origin: top; font-size: 0.65rem;">
            ₹
        </div>
    </div>
	<div class="sm:px-8 pr-4 sm:py-8 py-4 flex items-center sm:transform-none -translate-x-3 sm:block">
		<h1 class="font-medium text-gray-100">
			{{ $header }}
		</h1>
		<p class="text-stone-300 font-light sm:block hidden text-sm">{{ $desc }}</p>
	</div>
	<div class="sm:px-8 pl-2 pr-4 sm:py-8 py-4 flex items-center justify-end grow">
		<div class="text-stone-300 mx-3">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
			  <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
			  <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
			</svg>
		</div>
		<div class="text-stone-300 mx-3 relative">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
			  <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
			</svg>
			<span class="absolute right-0 top-0 ">
			  <span class="animate-ping absolute inline-flex right-0 top-0 h-2 w-2 rounded-full bg-red-500 opacity-75"></span>
			  <span class="absolute right-0 top-0 inline-flex rounded-full h-2 w-2 bg-red-600"></span>
			</span>
		</div>
        <button @click="open = true" class="text-stone-300 ml-3 relative block sm:hidden focus:outline-none">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
		</button>
		<div class="hidden sm:block text-yellow-400 bg-blue-500 p-0 rounded-full ml-3 mr-2">
			<svg class="h-11 w-11" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 128 128" version="1.1" viewBox="0 0 128 128" xml:space="preserve"><circle cx="64" cy="64" fill="#4B5F83" r="64"/><g><path d="M64,99h35c0-16-10.4-29-24.6-33.4C80.1,62,84,55.7,84,48.5c0-11-9-20-20-20" fill="#E6E6E6" /><path d="M64,28.5c-11,0-20,9-20,20c0,7.2,3.9,13.6,9.6,17.1C39.4,70,29,83,29,99h35" fill="#FFFFFF"/></g></svg>
		</div>
		<div class="hidden sm:block text-left ml-2">
			<div class="text-gray-700 font-medium text-sm">{{ $user->name }}</div>
			<div class="text-gray-300 font-light capitalize text-sm">{{ $user->user_type ?? 'Student' }} Account</div>
		</div>
	</div>
</div>
