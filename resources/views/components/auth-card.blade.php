<div class="min-h-screen flex flex-col sm:justify-center items-center px-3 pt-6 sm:pt-0 bg-gray-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md card mt-6">
        <div class="card-body px-6 mt-4 py-4">
            {{ $slot }}
        </div>
    </div>
</div>
