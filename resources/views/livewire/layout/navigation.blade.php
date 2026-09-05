<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ open: false }">
<nav :class="{ 'is-mobile-open': open }" class="app-sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" wire:navigate class="brand-mark">
            <span class="brand-symbol">H</span>
            <span><strong>HUMANLY</strong><small>PEOPLE OS</small></span>
        </a>
        <button @click="open = !open" class="sidebar-close sm:hidden" aria-label="Toggle navigation">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <div class="sidebar-content">
        <p class="sidebar-label">Workspace</p>
        <div class="sidebar-links">
            <a href="{{ route('dashboard') }}" wire:navigate class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="sidebar-icon">⌂</span><span>Overview</span>
            </a>
            @role('Super Admin')
                <a href="{{ route('super-admin.dashboard') }}" wire:navigate class="sidebar-link {{ request()->routeIs('super-admin.dashboard') ? 'is-active' : '' }}"><span class="sidebar-icon">◆</span><span>Admin Panel</span></a>
            @endrole
            @role('HR')
                <a href="{{ route('hr.dashboard') }}" wire:navigate class="sidebar-link {{ request()->routeIs('hr.dashboard') ? 'is-active' : '' }}"><span class="sidebar-icon">◎</span><span>HR Overview</span><span class="sidebar-badge">4</span></a>
            @endrole
            @role('Department Head')
                <a href="{{ route('department-head.dashboard') }}" wire:navigate class="sidebar-link {{ request()->routeIs('department-head.dashboard') ? 'is-active' : '' }}"><span class="sidebar-icon">▦</span><span>Department</span></a>
            @endrole
            @role('Employee')
                <a href="{{ route('employee.dashboard') }}" wire:navigate class="sidebar-link {{ request()->routeIs('employee.dashboard') ? 'is-active' : '' }}"><span class="sidebar-icon">◌</span><span>My Workspace</span></a>
            @endrole
            @hasanyrole('Super Admin|HR')
                <a href="{{ route('ats.jobs') }}" wire:navigate class="sidebar-link {{ request()->routeIs('ats.jobs') ? 'is-active' : '' }}"><span class="sidebar-icon">↗</span><span>Recruitment</span></a>
            @endhasanyrole
        </div>

        <p class="sidebar-label sidebar-label-spaced">Manage</p>
        <div class="sidebar-quick-note">
            <span class="sidebar-quick-icon">✦</span>
            <div><strong>People pulse</strong><small>Everything looks on track</small></div>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" wire:navigate class="sidebar-profile">
            <span class="avatar-initial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span class="min-w-0"><strong class="truncate block">{{ auth()->user()->name }}</strong><small class="truncate block">{{ auth()->user()->email }}</small></span>
            <span class="text-cyan-300">•••</span>
        </a>
        <button wire:click="logout" class="sidebar-logout"><span>↪</span> Sign out</button>
    </div>
</nav>

<div class="mobile-topbar sm:hidden">
    <a href="{{ route('dashboard') }}" wire:navigate class="brand-mark"><span class="brand-symbol">H</span><strong>HUMANLY</strong></a>
    <button @click="open = !open" class="mobile-menu-button" aria-label="Toggle navigation"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg></button>
</div>

{{-- Keep the existing role-aware links in the desktop shell above. --}}
{{--
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dynamic Navigation Links -->
                    @role('Super Admin')
                        <x-nav-link :href="route('super-admin.dashboard')" :active="request()->routeIs('super-admin.dashboard')">
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                    @endrole

                    @role('HR')
                        <x-nav-link :href="route('hr.dashboard')" :active="request()->routeIs('hr.dashboard')">
                            {{ __('HR Overview') }}
                        </x-nav-link>
                    @endrole

                    @role('Department Head')
                        <x-nav-link :href="route('department-head.dashboard')" :active="request()->routeIs('department-head.dashboard')">
                            {{ __('Department Panel') }}
                        </x-nav-link>
                    @endrole

                    @role('Employee')
                        <x-nav-link :href="route('employee.dashboard')" :active="request()->routeIs('employee.dashboard')">
                            {{ __('My Workspace') }}
                        </x-nav-link>
                    @endrole
                </div>
                @hasanyrole('Super Admin|HR')
                <x-nav-link :href="route('ats.jobs')" :active="request()->routeIs('ats.jobs')">
                    {{ __('Recruitment (ATS)') }}
                </x-nav-link>
                @endhasanyrole
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
             

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav> --}}
</div>
