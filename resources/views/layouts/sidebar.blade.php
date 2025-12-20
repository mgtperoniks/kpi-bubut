<aside class="w-64 bg-white shadow-lg min-h-screen">
    <div class="p-4 font-bold text-lg border-b">
        KPI Bubut
    </div>

    <nav class="p-4 text-sm space-y-2">

        <a href="{{ url('/dashboard') }}"
           class="block px-3 py-2 rounded
           {{ request()->is('dashboard') ? 'bg-blue-100 font-semibold' : 'hover:bg-gray-100' }}">
            Dashboard
        </a>

        <div class="pt-3 text-gray-500 text-xs uppercase">Input</div>

        <a href="{{ url('/produksi/input') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
            Produksi
        </a>
        <a href="{{ url('/reject/input') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
            Reject
        </a>
        <a href="{{ url('/downtime/input') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
            Downtime
        </a>

        <div class="pt-3 text-gray-500 text-xs uppercase">Tracking</div>

        <a href="{{ url('/tracking/operator') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
            Operator
        </a>
        <a href="{{ url('/tracking/mesin') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
            Mesin
        </a>
        <a href="{{ url('/downtime') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
            Downtime
        </a>

    </nav>
</aside>
