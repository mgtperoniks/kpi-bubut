<aside class="w-64 bg-white shadow-lg min-h-screen">
    <div class="p-4 font-bold text-lg border-b">
        Modul Bubut
    </div>

    <nav class="p-4 text-sm space-y-1">

        <a href="{{ url('/dashboard') }}"
           class="block px-3 py-2 rounded
           {{ request()->is('dashboard') ? 'bg-blue-100 font-semibold' : 'hover:bg-gray-100' }}">
            Dashboard
        </a>

        <div class="pt-2 text-gray-500 text-xs uppercase">Input</div>

        <a href="{{ url('/produksi/input') }}"
           class="block px-3 py-2 rounded
           {{ request()->is('produksi/*') ? 'bg-blue-100 font-semibold' : 'hover:bg-gray-100' }}">
            Input Produksi
        </a>

        <a href="{{ url('/downtime/input') }}"
           class="block px-3 py-2 rounded
           {{ request()->is('downtime/*') ? 'bg-blue-100 font-semibold' : 'hover:bg-gray-100' }}">
            Input Downtime
        </a>

        <div class="pt-2 text-gray-500 text-xs uppercase">Tracking</div>

        <a href="{{ url('/tracking/operator') }}"
           class="block px-3 py-2 rounded hover:bg-gray-100">
            Tracking Operator
        </a>

        <a href="{{ url('/tracking/mesin') }}"
           class="block px-3 py-2 rounded hover:bg-gray-100">
            Tracking Mesin
        </a>

    </nav>
</aside>
