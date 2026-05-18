<x-app-layout>
    <x-slot name="header">
         <div class="flex items-center justify-between">
             <div class="flex items-center gap-4">
                 <a href="{{ route('admin.dashboard') }}" class="text-[#8C8070] hover:text-[#C9A84C] transition-colors text-sm uppercase tracking-widest">← Admin</a>
                 <span class="text-[#C5BBB0]">/</span>
                 <div class="flex items-center gap-3">
                     <div class="w-8 h-8 border border-[#C9A84C] flex items-center justify-center text-[#C9A84C] text-xs">💰</div>
                     <h2 class="font-serif text-2xl text-[#1A1612] font-normal">
                         Ventas y <em class="italic text-[#C9A84C]">Reservas</em>
                     </h2>
                 </div>
             </div>
             <a href="{{ route('admin.ventas.exportar') }}"
                 class="border border-[#C9A84C] text-[#C9A84C] hover:bg-[#C9A84C] hover:text-[#1A1612] px-5 py-2 text-[11px] uppercase tracking-[0.15em] transition-colors">
                 Exportar CSV
             </a>
         </div>
     </x-slot>
 
     <div class="py-10 bg-[#F5F1EC] min-h-screen" x-data="{ cancelModal: false, cancelCodigo: '', tab: 'reservas' }">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 
             {{-- KPIs de ventas --}}
             <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                 <div class="bg-white border border-[#C5BBB0]/30 p-5">
                     <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Reservas hoy</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">{{ $kpis['reservas_hoy'] ?? 0 }}</p>
                    <p class="text-[11px] text-[#2E6E6A]">Datos desde BD</p>
                 </div>
                 <div class="bg-white border border-[#C5BBB0]/30 p-5">
                     <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Ingresos del mes</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">${{ $kpis['ingresos_mes'] ?? '0.00' }}</p>
                    <p class="text-[11px] text-[#2E6E6A]">Mes actual</p>
                 </div>
                 <div class="bg-white border border-[#C5BBB0]/30 p-5">
                     <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Ocupación promedio</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">{{ $kpis['ocupacion'] ?? '0%' }}</p>
                     <p class="text-[11px] text-[#8C8070]">Capacidad óptima</p>
                 </div>
                 <div class="bg-white border border-[#C5BBB0]/30 p-5">
                     <p class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070] mb-2">Cancelaciones</p>
                    <p class="font-serif text-3xl text-[#1A1612] leading-none mb-1">{{ $kpis['cancelaciones'] ?? 0 }}</p>
                     <p class="text-[11px] text-[#8B2020]">Este mes</p>
                 </div>
             </div>
 
             {{-- Tabs: Reservas / Estadísticas --}}
             <div class="flex border-b border-[#C5BBB0]/30 mb-8">
                 <button @click="tab = 'reservas'"
                     :class="tab === 'reservas' ? 'text-[#C9A84C] border-b-2 border-[#C9A84C]' : 'text-[#8C8070] hover:text-[#1A1612]'"
                     class="px-6 py-3 text-[11px] uppercase tracking-[0.15em] transition-colors -mb-px">
                     Reservas recientes
                 </button>
                 <button @click="tab = 'estadisticas'"
                     :class="tab === 'estadisticas' ? 'text-[#C9A84C] border-b-2 border-[#C9A84C]' : 'text-[#8C8070] hover:text-[#1A1612]'"
                     class="px-6 py-3 text-[11px] uppercase tracking-[0.15em] transition-colors -mb-px">
                     Estadísticas
                 </button>
             </div>
 
             {{-- ── PANEL: RESERVAS ── --}}
             <div x-show="tab === 'reservas'">
                 {{-- Filtros --}}
                 <div class="flex flex-col sm:flex-row gap-3 mb-6">
                     <input type="text" placeholder="Buscar por código o visitante…"
                         class="border border-[#C5BBB0]/50 bg-white px-4 py-2 text-sm text-[#1A1612] placeholder-[#8C8070] focus:outline-none focus:border-[#C9A84C] flex-1 rounded-none">
                     <select class="border border-[#C5BBB0]/50 bg-white px-4 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                         <option value="">Todas las exposiciones</option>
                        @foreach($exposiciones ?? [] as $expo)
                         <option>{{ is_object($expo) ? $expo->nombre : $expo }}</option>
                         @endforeach
                     </select>
                     <select class="border border-[#C5BBB0]/50 bg-white px-4 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                         <option value="">Todos los estados</option>
                         <option value="confirmed">Confirmada</option>
                         <option value="pending">Pendiente</option>
                         <option value="cancelled">Cancelada</option>
                     </select>
                 </div>
 
                 <div class="bg-white border border-[#C5BBB0]/30 overflow-hidden mb-8">
                     <div class="overflow-x-auto">
                         <table class="w-full border-collapse">
                             <thead>
                                 <tr class="bg-[#1A1612]">
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Código</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Visitante</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Exposición</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Fecha</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Hora</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Total</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Estado</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#C9A84C] px-5 py-3 font-normal">Acción</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @forelse($reservas ?? [] as $reserva)
                                 <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03] transition-colors">
                                     <td class="px-5 py-3 text-[12px] font-mono text-[#8C8070] bg-[#F5F1EC] tracking-wider">{{ $reserva->codigo }}</td>
                                     <td class="px-5 py-3 text-[13px] text-[#1A1612]">{{ $reserva->visitante }}</td>
                                     <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $reserva->exposicion }}</td>
                                     <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $reserva->fecha }}</td>
                                     <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $reserva->hora }}</td>
                                     <td class="px-5 py-3 text-[13px] text-[#1A1612] font-medium">${{ $reserva->total }}</td>
                                     <td class="px-5 py-3">
                                         @if($reserva->estado === 'confirmed')
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#E8F4EE] text-[#1A6B3C]">Confirmada</span>
                                         @elseif($reserva->estado === 'pending')
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#FDF3DC] text-[#7A5200]">Pendiente</span>
                                         @else
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#F0EDE8] text-[#6B6257]">Cancelada</span>
                                         @endif
                                     </td>
                                     <td class="px-5 py-3">
                                         @if($reserva->estado !== 'cancelled')
                                        <button @click="cancelModal = true; cancelCodigo = @js($reserva->codigo)"
                                             class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#8B2020] hover:border-[#8B2020] hover:bg-[#8B2020] hover:text-white transition-colors">
                                             Cancelar
                                         </button>
                                         @else
                                         <span class="text-[#C5BBB0] text-[12px]">—</span>
                                         @endif
                                     </td>
                                 </tr>
                                 @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-[13px] text-[#8C8070]">
                                        No hay reservas en la base de datos todavía.
                                     </td>
                                 </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>
                     @if(isset($reservas) && $reservas->hasPages())
                     <div class="px-6 py-4 border-t border-[#C5BBB0]/15">
                         {{ $reservas->links() }}
                     </div>
                     @endif
                 </div>
             </div>
 
             {{-- ── PANEL: ESTADÍSTICAS ── --}}
             <div x-show="tab === 'estadisticas'">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
 
                     {{-- Reservas por exposición --}}
                     <div class="bg-white border border-[#C5BBB0]/30 overflow-hidden">
                         <div class="bg-[#1A1612] px-6 py-4">
                             <p class="font-serif text-base text-[#C9A84C]">Reservas por exposición</p>
                         </div>
                         <table class="w-full border-collapse">
                             <thead>
                                 <tr class="border-b border-[#C5BBB0]/15">
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Exposición</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Reservas</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Ingresos</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @forelse($estadisticas['por_exposicion'] ?? [] as $stat)
                                 <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03]">
                                     <td class="px-5 py-3 text-[13px] text-[#1A1612]">{{ $stat->nombre }}</td>
                                     <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $stat->reservas }}</td>
                                     <td class="px-5 py-3 text-[13px] font-medium text-[#1A1612]">${{ number_format($stat->ingresos) }}</td>
                                 </tr>
                                 @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-[13px] text-[#8C8070]">
                                        Aún no hay estadísticas por exposición.
                                    </td>
                                 </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>
 
                     {{-- Horarios más populares --}}
                     <div class="bg-white border border-[#C5BBB0]/30 overflow-hidden">
                         <div class="bg-[#1A1612] px-6 py-4">
                             <p class="font-serif text-base text-[#C9A84C]">Horarios más populares</p>
                         </div>
                         <table class="w-full border-collapse">
                             <thead>
                                 <tr class="border-b border-[#C5BBB0]/15">
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Horario</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Ocupación</th>
                                     <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Estado</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @forelse($estadisticas['horarios_populares'] ?? [] as $horario)
                                 <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03]">
                                     <td class="px-5 py-3 font-serif text-base text-[#1A1612]">{{ $horario->hora }} h</td>
                                     <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $horario->ocupacion }}%</td>
                                     <td class="px-5 py-3">
                                         @if($horario->ocupacion >= 100)
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#FAEAEA] text-[#8B2020]">Lleno</span>
                                         @elseif($horario->ocupacion >= 85)
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#FAEAEA] text-[#8B2020]">Casi lleno</span>
                                         @elseif($horario->ocupacion >= 60)
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#FDF3DC] text-[#7A5200]">Alto</span>
                                         @else
                                             <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#E8F4EE] text-[#1A6B3C]">Disponible</span>
                                         @endif
                                     </td>
                                 </tr>
                                 @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-[13px] text-[#8C8070]">
                                        Aún no hay estadísticas de horarios.
                                    </td>
                                 </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>
 
                 </div>
             </div>
 
         </div>
 
         {{-- ── MODAL: CANCELAR RESERVA ── --}}
         <div x-show="cancelModal" x-cloak
             class="fixed inset-0 bg-[#1A1612]/70 z-50 flex items-center justify-center"
             @click.self="cancelModal = false">
             <div class="bg-white border border-[#C9A84C]/30 w-full max-w-md overflow-hidden">
                 <div class="bg-[#1A1612] px-6 py-4 flex items-center justify-between">
                     <p class="font-serif text-lg text-[#C9A84C]">Cancelar Reserva</p>
                     <button @click="cancelModal = false" class="text-[#8C8070] hover:text-[#C9A84C] text-xl leading-none transition-colors">✕</button>
                 </div>
                 <div class="p-6 text-center">
                     <div class="text-4xl mb-4">⚠️</div>
                     <p class="font-serif text-xl text-[#1A1612] mb-2">
                         ¿Cancelar reserva <span class="italic text-[#C9A84C]" x-text="cancelCodigo"></span>?
                     </p>
                     <p class="text-sm text-[#8C8070] mb-6">Esta acción no se puede deshacer. El visitante será notificado por correo electrónico.</p>
                    <div class="flex justify-center gap-3">
                        <button @click="cancelModal = false"
                            class="border border-[#C5BBB0]/40 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#1A1612] hover:border-[#C9A84C] transition-colors">
                            Volver
                        </button>
                        <form method="POST" :action="`/admin/reservas/${cancelCodigo}/cancelar`">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="border border-[#8B2020]/30 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#8B2020] hover:bg-[#8B2020] hover:text-white hover:border-[#8B2020] transition-colors">
                                Sí, cancelar reserva
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>