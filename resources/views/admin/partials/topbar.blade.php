<!-- Top Bar -->
<div class="sticky top-0 z-40 bg-white shadow-md">
    <div class="flex items-center justify-between h-16 px-6">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        
        <div class="flex items-center space-x-4">
            <!-- Language Switcher -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-1 px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 border rounded-lg">
                    <i class="fas fa-globe"></i>
                    <span>{{ app()->getLocale() == 'id' ? 'ID' : 'EN' }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border z-50">
                    <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-50 {{ app()->getLocale() == 'en' ? 'text-cyan-600 font-medium' : 'text-gray-700' }}">
                        <span class="mr-2">🇺🇸</span> English
                    </a>
                    <a href="{{ route('language.switch', 'id') }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-50 {{ app()->getLocale() == 'id' ? 'text-cyan-600 font-medium' : 'text-gray-700' }}">
                        <span class="mr-2">🇮🇩</span> Indonesia
                    </a>
                </div>
            </div>
            
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
