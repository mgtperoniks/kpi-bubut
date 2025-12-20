<aside class="w-64 min-h-screen">
    <div class="p-4 border-b">
        <h1 class="text-lg font-semibold">KPI Bubut</h1>
    </div>

    <nav class="p-4 text-sm">

        <a href="{{ url('/dashboard') }}"
           class="{{ request()->is('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <div class="section-title">Input</div>

        <a href="{{ url('/produksi/input') }}"
           class="{{ request()->is('produksi*') ? 'active' : '' }}">
            Produksi
        </a>

        <a href="{{ url('/reject/input') }}"
           class="{{ request()->is('reject*') ? 'active' : '' }}">
            Reject
        </a>

        <a href="{{ url('/downtime/input') }}"
           class="{{ request()->is('downtime/input') ? 'active' : '' }}">
            Downtime
        </a>

        <div class="section-title">Tracking</div>

        <a href="{{ url('/tracking/operator') }}"
           class="{{ request()->is('tracking/operator') ? 'active' : '' }}">
            Operator
        </a>

        <a href="{{ url('/tracking/mesin') }}"
           class="{{ request()->is('tracking/mesin') ? 'active' : '' }}">
            Mesin
        </a>

        <a href="{{ url('/downtime') }}"
           class="{{ request()->is('downtime') ? 'active' : '' }}">
            Downtime
        </a>

    </nav>
</aside>
