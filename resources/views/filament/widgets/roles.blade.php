<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $roles = auth()->user()->getRoles()
        @endphp
        <h1 class="mb-5">Your Role: </h1>
        @foreach ($roles as $item)
            <span class="border rounded-lg p-2">{{ $item }}</span>
        @endforeach
    </x-filament::section>
</x-filament-widgets::widget>
