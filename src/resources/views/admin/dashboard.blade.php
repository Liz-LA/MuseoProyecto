<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-9 h-9 border border-[#C9A84C] flex items-center justify-center text-[#C9A84C] font-serif text-sm">⚙</div>
            <h2 class="font-serif text-2xl text-[#1A1612] font-normal">
                Panel de <em class="italic text-[#C9A84C]">Administración</em>
            </h2>
        </div>
    </x-slot>
 
    <div class="py-10 bg-[#F5F1EC] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 
            {{-- KPIs --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white border border-[#C5BBB0]/30 p-5">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Reservas hoy</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">{{ $kpis['reservas_hoy'] ?? 0 }}</p>
                    <p class="text-[11px] text-[#8C8070]">Datos desde BD</p>
                </div>
                <div class="bg-white border border-[#C5BBB0]/30 p-5">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Visitantes esta semana</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">{{ $kpis['visitantes_semana'] ?? 0 }}</p>
                    <p class="text-[11px] text-[#8C8070]">Semana actual</p>
                </div>
                <div class="bg-white border border-[#C5BBB0]/30 p-5">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Ocupación promedio</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">{{ $kpis['ocupacion'] ?? 0 }}%</p>
                    <p class="text-[11px] text-[#8C8070]">Capacidad óptima</p>
                </div>
                <div class="bg-white border border-[#C5BBB0]/30 p-5">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Ingresos del mes</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">${{ number_format($kpis['ingresos_mes'] ?? 0, 2) }}</p>
                    <p class="text-[11px] text-[#8C8070]">Mes actual</p>
                </div>
            </div>
 
            {{-- Secciones de administración --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
 
                {{-- Usuarios --}}
                <a href="{{ route('admin.usuarios') }}" class="group bg-white border border-[#C5BBB0]/30 hover:border-[#C9A84C] transition-colors overflow-hidden block">
                    <div class="bg-[#1A1612] px-6 py-5">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#C9A84C] mb-1">Módulo 01</p>
                        <p class="font-serif text-xl text-[#FAF8F4]">Usuarios</p>
                    </div>
                    <div class="p-6">
                        <p class="text-[13px] text-[#8C8070] leading-relaxed mb-6">Gestiona las cuentas registradas: administradores y visitantes. Cambia roles, activa o desactiva accesos.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] uppercase tracking-[0.15em] text-[#C9A84C] group-hover:underline">Gestionar →</span>
                            <span class="text-[10px] text-[#C5BBB0]">{{ $moduleCounts['usuarios'] ?? 0 }} registrados</span>
                        </div>
                    </div>
                </a>
 
                {{-- Obras / Exposiciones --}}
                <a href="{{ route('admin.exposiciones') }}" class="group bg-white border border-[#C5BBB0]/30 hover:border-[#C9A84C] transition-colors overflow-hidden block">
                    <div class="bg-[#1A1612] px-6 py-5">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#C9A84C] mb-1">Módulo 02</p>
                        <p class="font-serif text-xl text-[#FAF8F4]">Obras y Exposiciones</p>
                    </div>
                    <div class="p-6">
                        <p class="text-[13px] text-[#8C8070] leading-relaxed mb-6">Administra el catálogo de exposiciones, obras por sala, horarios de visita y capacidades disponibles.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] uppercase tracking-[0.15em] text-[#C9A84C] group-hover:underline">Gestionar →</span>
                            <span class="text-[10px] text-[#C5BBB0]">{{ $moduleCounts['exposiciones'] ?? 0 }} exposiciones</span>
                        </div>
                    </div>
                </a>
 
                {{-- Ventas --}}
                <a href="{{ route('admin.ventas') }}" class="group bg-white border border-[#C5BBB0]/30 hover:border-[#C9A84C] transition-colors overflow-hidden block">
                    <div class="bg-[#1A1612] px-6 py-5">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#C9A84C] mb-1">Módulo 03</p>
                        <p class="font-serif text-xl text-[#FAF8F4]">Ventas y Reservas</p>
                    </div>
                    <div class="p-6">
                        <p class="text-[13px] text-[#8C8070] leading-relaxed mb-6">Consulta y gestiona las reservas activas. Cancela entradas, exporta reportes y revisa estadísticas de ingresos.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] uppercase tracking-[0.15em] text-[#C9A84C] group-hover:underline">Gestionar →</span>
                            <span class="text-[10px] text-[#C5BBB0]">{{ $moduleCounts['reservas_hoy'] ?? 0 }} reservas hoy</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>