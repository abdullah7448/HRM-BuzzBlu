@props(['title', 'description' => null, 'icon' => null])

<div class="dashboard-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-4">
            @if($icon)
                <div class="dashboard-header-icon">
                    {!! $icon !!}
                </div>
            @endif
            <div class="flex-1">
                <p class="dashboard-kicker">People operations / workspace</p>
                <h1 class="text-3xl font-bold tracking-tight">{{ $title }}</h1>
                @if($description)
                    <p class="mt-1 text-cyan-100">{{ $description }}</p>
                @endif
            </div>
            <div class="hidden md:flex items-center gap-2 dashboard-live-pill">
                <span class="dashboard-live-dot"></span>
                Live workspace
            </div>
        </div>
    </div>
</div>
