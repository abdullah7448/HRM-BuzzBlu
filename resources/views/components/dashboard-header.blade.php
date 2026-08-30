@props(['title', 'description' => null, 'icon' => null])

<div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white shadow-elevation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-4">
            @if($icon)
                <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                    {!! $icon !!}
                </div>
            @endif
            <div class="flex-1">
                <h1 class="text-3xl font-bold tracking-tight">{{ $title }}</h1>
                @if($description)
                    <p class="mt-2 text-primary-100">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
