<x-app-layout>
    <x-slot name="header">
        <style>
            @keyframes text-shimmer {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-text-shimmer {
                background-size: 200% auto;
                animation: text-shimmer 3s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-5px) rotate(5deg); }
            }
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
        </style>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-2 group">
                <div class="p-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-100/10 group-hover:scale-110 transition-transform duration-300">
                     <svg class="w-6 h-6 text-blue-600 animate-float" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black leading-tight tracking-tight text-gray-900 dark:text-gray-100">
                    {{ __('Mi') }} 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-500 to-blue-600 animate-text-shimmer relative">
                        {{ __('Perfil') }}
                        <svg class="absolute -top-1 -right-4 w-4 h-4 text-yellow-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 9a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-9a1 1 0 011 1v2h2a1 1 0 110 2h-2v2a1 1 0 11-2 0V6h-2a1 1 0 110-2h2V3a1 1 0 011-1z"/></svg>
                    </span>
                </h2>
            </div>
            
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors group">
                            <svg class="w-4 h-4 mr-2 group-hover:animate-bounce" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-medium text-gray-400 dark:text-gray-500 md:ml-2">Perfil</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 relative min-h-screen overflow-hidden">

        <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-blue-600/10 via-purple-600/5 to-transparent -z-10 blur-3xl opacity-60"></div>
        <div class="absolute top-20 right-20 w-96 h-96 bg-purple-500/20 rounded-full blur-[120px] -z-10 animate-pulse"></div>
        <div class="absolute top-40 left-10 w-96 h-96 bg-blue-500/20 rounded-full blur-[120px] -z-10 animate-pulse" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            <div class="relative overflow-hidden rounded-[2.5rem] bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/40 dark:border-gray-700/40 shadow-2xl p-8 sm:p-12 text-center sm:text-left flex flex-col sm:flex-row items-center gap-10 group transition-all hover:bg-white/50 dark:hover:bg-gray-800/50">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                <div class="relative z-10 shrink-0 group-hover:scale-105 transition-transform duration-500">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 p-[3px] shadow-lg shadow-purple-500/30">
                        <div class="w-full h-full rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-purple-600 uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-4 border-white dark:border-gray-900 rounded-full" title="Activo"></div>
                </div>

                <div class="relative z-10 space-y-3 flex-1">
                    <h3 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">
                        Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-500">{{ explode(' ', Auth::user()->name)[0] }}</span> 👋
                    </h3>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl font-medium leading-relaxed">
                        Bienvenido a tu centro de mando. Personaliza tu experiencia, gestiona tu seguridad y mantén tus datos al día.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-2 justify-center sm:justify-start">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                           {{ Auth::user()->email }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Usuario desde {{ Auth::user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

                <div class="space-y-10">
                    <div class="p-8 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md shadow-xl sm:rounded-3xl border border-white/50 dark:border-gray-700/50 relative overflow-hidden group hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-1">
                        <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-[0.03] group-hover:opacity-10 transition duration-500 pointer-events-none">
                            <svg class="w-40 h-40 transform rotate-12 -mr-10 -mt-10" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="relative z-10">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-8 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md shadow-xl sm:rounded-3xl border border-white/50 dark:border-gray-700/50 relative overflow-hidden group hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 hover:-translate-y-1">
                         <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-[0.03] group-hover:opacity-10 transition duration-500 pointer-events-none">
                            <svg class="w-40 h-40 transform rotate-12 -mr-10 -mt-10" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="relative z-10">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="space-y-10 lg:sticky lg:top-24">
                     <div class="p-8 bg-red-50/60 dark:bg-red-900/10 backdrop-blur-md shadow-xl sm:rounded-3xl border border-red-100 dark:border-red-900/30 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-red-500/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition duration-700"></div>
                        <div class="relative z-10">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
