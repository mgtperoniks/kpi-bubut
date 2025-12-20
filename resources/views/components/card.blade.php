<div class="bg-white rounded-lg shadow p-6 mb-6">
    @isset($title)
        <h2 class="text-lg font-semibold mb-4">{{ $title }}</h2>
    @endisset
    {{ $slot }}
</div>
