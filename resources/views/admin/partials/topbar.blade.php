<!-- Top Bar -->
<div class="sticky top-0 z-40 bg-white shadow-md">
    <div class="flex items-center justify-between h-16 px-6">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</div>
