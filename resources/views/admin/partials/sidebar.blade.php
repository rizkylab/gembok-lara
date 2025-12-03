<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 via-blue-900 to-cyan-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0" 
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-black bg-opacity-30 border-b border-cyan-500/20">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fas fa-network-wired text-white"></i>
            </div>
            <span class="text-white font-bold text-xl tracking-wide">GEMBOK LARA</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 px-4 space-y-2 overflow-y-auto" style="max-height: calc(100vh - 140px);">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-home mr-3"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.customers.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-users mr-3"></i>
            <span>Customers</span>
        </a>
        
        <a href="{{ route('admin.packages.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.packages.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-box mr-3"></i>
            <span>Packages</span>
        </a>
        
        <a href="{{ route('admin.invoices.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.invoices.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-file-invoice mr-3"></i>
            <span>Invoices</span>
        </a>
        
        <a href="{{ route('admin.technicians.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.technicians.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-tools mr-3"></i>
            <span>Technicians</span>
        </a>
        
        <a href="{{ route('admin.collectors.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.collectors.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-hand-holding-usd mr-3"></i>
            <span>Collectors</span>
        </a>
        
        <a href="{{ route('admin.agents.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.agents.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-user-tie mr-3"></i>
            <span>Agents</span>
        </a>
        
        <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.vouchers.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-ticket-alt mr-3"></i>
            <span>Vouchers</span>
        </a>
        
        <a href="{{ route('admin.network.map') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.network.*') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-map-marked-alt mr-3"></i>
            <span>Network Map</span>
        </a>
        
        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition {{ request()->routeIs('admin.settings') ? 'bg-white bg-opacity-20 text-white' : '' }}">
            <i class="fas fa-cog mr-3"></i>
            <span>Settings</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="absolute bottom-0 w-full p-4">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 text-gray-300 hover:bg-red-600 hover:text-white rounded-lg transition">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
