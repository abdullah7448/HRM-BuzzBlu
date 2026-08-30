@props(['tabs' => [], 'activeTab' => null])

<div class="w-full">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 bg-white rounded-t-xl shadow-card">
        <div class="flex overflow-x-auto">
            @foreach($tabs as $key => $tab)
                <button
                    @click="activeTab = '{{ $key }}'"
                    :class="{
                        'border-b-2 border-primary-600 text-primary-600': activeTab === '{{ $key }}',
                        'border-b-2 border-transparent text-gray-600 hover:text-gray-800': activeTab !== '{{ $key }}'
                    }"
                    class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors duration-200 hover:bg-gray-50"
                >
                    <span class="inline-flex items-center gap-2">
                        @if(isset($tab['icon']))
                            {!! $tab['icon'] !!}
                        @endif
                        {{ $tab['label'] ?? $key }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-b-xl shadow-card overflow-hidden">
        @foreach($tabs as $key => $tab)
            <div
                x-show="activeTab === '{{ $key }}'"
                x-transition
                class="p-6"
            >
                @if(isset($tab['content']))
                    {!! $tab['content'] !!}
                @elseif(isset($tab['component']))
                    @livewire($tab['component'])
                @endif
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tabs', () => ({
                activeTab: '{{ $activeTab ?? array_key_first($tabs) ?? 0 }}',
            }))
        })
    </script>
@endpush
