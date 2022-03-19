<div class="flex justify-start">
	<div class="px-8 py-8 bg-white border-l">
		<h1 class="font-semibold text-gray-700">
			{{ $header }}
		</h1>
		<p class="text-stone-400 font-light text-sm">{{ $desc }}</p>
	</div>
	<div class="px-8 py-8 bg-white flex items-center justify-end grow">
		<div class="text-stone-300 mx-3">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
			  <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
			  <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
			</svg>
		</div>
		<div class="text-stone-300 mx-3 relative">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
			  <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
			</svg>
			<span class="absolute right-0 top-0 ">
			  <span class="animate-ping absolute inline-flex right-0 top-0 h-2 w-2 rounded-full bg-red-500 opacity-75"></span>
			  <span class="absolute right-0 top-0 inline-flex rounded-full h-2 w-2 bg-red-600"></span>
			</span>
		</div>
		<div class="text-yellow-400 bg-blue-500 p-0 rounded-full ml-3 mr-2">
			<svg class="h-11 w-11" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 128 128" version="1.1" viewBox="0 0 128 128" xml:space="preserve"><circle cx="64" cy="64" fill="#4B5F83" r="64"/><g><path d="M64,99h35c0-16-10.4-29-24.6-33.4C80.1,62,84,55.7,84,48.5c0-11-9-20-20-20" fill="#E6E6E6" /><path d="M64,28.5c-11,0-20,9-20,20c0,7.2,3.9,13.6,9.6,17.1C39.4,70,29,83,29,99h35" fill="#FFFFFF"/></g></svg>
		</div>
		<div class="text-left mx-2">
			<div class="text-gray-700 font-medium text-sm">Admin</div>
			<div class="text-gray-300 font-light text-sm">Admin Account</div>
		</div>
	</div>
</div>