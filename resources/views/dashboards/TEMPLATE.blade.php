{{-- 
Advanced Dashboard Template with Sub-tabs
Usage in your blade files:

<div x-data="{ activeTab: 'tab1', subTab: { tab1: 'sub1', tab2: 'sub1' } }">
    <!-- Main Tabs -->
    <div class="tab-nav">
        <div class="tab-nav-container">
            <button @click="activeTab = 'tab1'" :class="activeTab === 'tab1' ? 'tab-btn-active' : 'tab-btn-inactive'" class="tab-btn">
                Tab 1
            </button>
        </div>
    </div>

    <!-- Tab Content with Sub-tabs -->
    <div x-show="activeTab === 'tab1'" x-transition class="space-y-6">
        <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
            <div class="sub-tab-nav">
                <button @click="subTab.tab1 = 'sub1'" :class="subTab.tab1 === 'sub1' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'" class="sub-tab-btn">
                    Sub Tab 1
                </button>
            </div>
            
            <div x-show="subTab.tab1 === 'sub1'" x-transition>
                <!-- Content Here -->
            </div>
        </div>
    </div>
</div>

Color Reference:
- stat-card-primary: Blue
- stat-card-success: Green
- stat-card-warning: Amber
- stat-card-secondary: Purple

Icon SVGs are available from Heroicons (fill="none" stroke="currentColor")

For Livewire components use:
<livewire:component.name />

--}}
