<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-[#8C8070] hover:text-[#C9A84C] transition-colors text-sm uppercase tracking-widest">← Admin</a>
                <span class="text-[#C5BBB0]">/</span>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 border border-[#C9A84C] flex items-center justify-center text-[#C9A84C] text-xs">🏛</div>
                    <h2 class="font-serif text-2xl text-[#1A1612] font-normal">
                        Obras y <em class="italic text-[#C9A84C]">Exposiciones</em>
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F5F1EC] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pestañas --}}
            <div class="flex border-b border-[#C5BBB0]/30 mb-8" x-data="{ tab: 'exposiciones' }">
                <button @click="tab = 'exposiciones'"
                    :class="tab === 'exposiciones' ? 'text-[#C9A84C] border-b-2 border-[#C9A84C]' : 'text-[#8C8070] hover:text-[#1A1612]'"
                    class="px-6 py-3 text-[11px] uppercase tracking-[0.15em] transition-colors -mb-px">
                    Exposiciones
                </button>
                <button @click="tab = 'horarios'"
                    :class="tab === 'horarios' ? 'text-[#C9A84C] border-b-2 border-[#C9A84C]' : 'text-[#8C8070] hover:text-[#1A1612]'"
                    class="px-6 py-3 text-[11px] uppercase tracking-[0.15em] transition-colors -mb-px">
                    Horarios
                </button>

                {{-- Panel: Exposiciones --}}
                <div class="ml-auto flex items-center gap-3">
                    <button x-show="tab === 'exposiciones'" @click="$dispatch('open-expo-modal')"
                        class="bg-[#C9A84C] hover:bg-[#E8D08A] text-[#1A1612] px-5 py-2 text-[11px] font-medium uppercase tracking-[0.15em] transition-colors">
                        + Crear exposición
                    </button>
                    <button x-show="tab === 'horarios'" @click="$dispatch('open-horario-modal')"
                        class="bg-[#C9A84C] hover:bg-[#E8D08A] text-[#1A1612] px-5 py-2 text-[11px] font-medium uppercase tracking-[0.15em] transition-colors">
                        + Crear horario
                    </button>
                </div>

            </div>

            {{-- Alpine scope para tabs y modales --}}
            <div x-data="{
                    tab: 'exposiciones',
                    expoModal: false,
                    horarioModal: false,
                    confirmModal: false,
                    editingExpo: null,
                    editingHorario: null,
                    deleteTarget: null,
                    deleteType: null
                }"
                @open-expo-modal.window="expoModal = true; editingExpo = null"
                @open-horario-modal.window="horarioModal = true; editingHorario = null"
            >

                {{-- ── GRID: EXPOSICIONES ── --}}
                <div>
                    {{-- Grid de exposiciones --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

                        @forelse($exposiciones ?? [] as $expo)
                        <div class="bg-white border border-[#C5BBB0]/30 hover:border-[#C9A84C]/50 transition-colors overflow-hidden">
                            <div class="h-20 flex items-center justify-center text-4xl"
                                style="background: {{ $expo->color ?? '#F5F0E8' }}">
                                {{ $expo->emoji ?? '🖼️' }}
                            </div>
                            <div class="p-4">
                                <p class="font-serif text-sm text-[#1A1612] mb-1">{{ $expo->nombre }}</p>
                                <p class="text-[11px] text-[#8C8070] mb-3">{{ $expo->sala }} · ${{ $expo->precio }} MXN · {{ $expo->tipo }}</p>
                                <div class="flex gap-2">
                                    <button @click="editingExpo = {{ $expo->id }}; expoModal = true"
                                        class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#1A1612] hover:border-[#C9A84C] hover:text-[#C9A84C] transition-colors">
                                        Editar
                                    </button>
                                    <button @click="deleteTarget = {{ $expo->id }}; deleteType = 'expo'; confirmModal = true"
                                        class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#8B2020] hover:border-[#8B2020] hover:bg-[#8B2020] hover:text-white transition-colors">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full bg-white border border-[#C5BBB0]/30 p-8 text-center text-[13px] text-[#8C8070]">
                            No hay exposiciones registradas en la base de datos todavía.
                        </div>
                        @endforelse

                    </div>
                </div>

                {{-- ── PANEL: HORARIOS ── --}}
                <div class="bg-white border border-[#C5BBB0]/30 overflow-hidden mb-8">
                    <div class="bg-[#1A1612] px-6 py-4">
                        <p class="font-serif text-base text-[#C9A84C]">Horarios de visita</p>
                    </div>
                    <div class="divide-y divide-[#C5BBB0]/10">
                        @forelse($horarios ?? [] as $horario)
                        <div class="flex items-center gap-5 px-6 py-4 hover:bg-[#C9A84C]/[0.03] transition-colors">
                            <span class="font-serif text-xl text-[#1A1612] w-20">{{ $horario->time }}</span>
                            <span class="flex-1 text-[13px] text-[#8C8070]">Capacidad: {{ $horario->capacidad }} personas &nbsp;·&nbsp; {{ $horario->dias }}</span>
                            <div class="flex gap-2">
                                <button @click="editingHorario = {{ $horario->id }}; horarioModal = true"
                                    class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#1A1612] hover:border-[#C9A84C] hover:text-[#C9A84C] transition-colors">
                                    Editar
                                </button>
                                <button @click="deleteTarget = {{ $horario->id }}; deleteType = 'horario'; confirmModal = true"
                                    class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#8B2020] hover:border-[#8B2020] hover:bg-[#8B2020] hover:text-white transition-colors">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-[13px] text-[#8C8070]">
                            No hay horarios registrados en la base de datos todavía.
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── MODAL: EXPOSICIÓN ── --}}
                <div x-show="expoModal" x-cloak
                    class="fixed inset-0 bg-[#1A1612]/70 z-50 flex items-center justify-center"
                    @click.self="expoModal = false">
                    <div class="bg-white border border-[#C9A84C]/30 w-full max-w-lg overflow-hidden">
                        <div class="bg-[#1A1612] px-6 py-4 flex items-center justify-between">
                            <p class="font-serif text-lg text-[#C9A84C]" x-text="editingExpo ? 'Editar Exposición' : 'Nueva Exposición'">Nueva Exposición</p>
                            <button @click="expoModal = false" class="text-[#8C8070] hover:text-[#C9A84C] text-xl leading-none transition-colors">✕</button>
                        </div>
                        <div class="p-6">
                            <form method="POST" :action="editingExpo ? `/admin/exposiciones/${editingExpo}` : '{{ route('admin.exposiciones.store') }}'">
                                @csrf
                                <template x-if="editingExpo"><input type="hidden" name="_method" value="PUT"></template>
                                <div class="flex flex-col gap-1.5 mb-4">
                                    <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Nombre</label>
                                    <input type="text" name="nombre" placeholder="Nombre de la exposición" required
                                        class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Sala</label>
                                        <select name="sala" class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                            @foreach(['A','B','C','D','E','F'] as $sala)
                                            <option>Sala {{ $sala }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Precio (MXN)</label>
                                        <input type="number" name="precio" placeholder="120" min="0" step="0.01"
                                            class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5 mb-4">
                                    <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Tipo</label>
                                    <select name="tipo" class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                        <option>Permanente</option>
                                        <option>Temporal</option>
                                        <option>Especial</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5 mb-6">
                                    <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Descripción</label>
                                    <input type="text" name="descripcion" placeholder="Breve descripción de la exposición…"
                                        class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="expoModal = false"
                                        class="border border-[#C5BBB0]/40 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#1A1612] hover:border-[#C9A84C] transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="bg-[#C9A84C] hover:bg-[#E8D08A] text-[#1A1612] px-5 py-2 text-[11px] font-medium uppercase tracking-[0.15em] transition-colors">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ── MODAL: HORARIO ── --}}
                <div x-show="horarioModal" x-cloak
                    class="fixed inset-0 bg-[#1A1612]/70 z-50 flex items-center justify-center"
                    @click.self="horarioModal = false">
                    <div class="bg-white border border-[#C9A84C]/30 w-full max-w-md overflow-hidden">
                        <div class="bg-[#1A1612] px-6 py-4 flex items-center justify-between">
                            <p class="font-serif text-lg text-[#C9A84C]" x-text="editingHorario ? 'Editar Horario' : 'Nuevo Horario'">Nuevo Horario</p>
                            <button @click="horarioModal = false" class="text-[#8C8070] hover:text-[#C9A84C] text-xl leading-none transition-colors">✕</button>
                        </div>
                        <div class="p-6">
                            <form method="POST" :action="editingHorario ? `/admin/horarios/${editingHorario}` : '{{ route('admin.horarios.store') }}'">
                                @csrf
                                <template x-if="editingHorario"><input type="hidden" name="_method" value="PUT"></template>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Hora inicio</label>
                                        <input type="time" name="hora" value="10:00" required
                                            class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Capacidad máxima</label>
                                        <input type="number" name="capacidad" placeholder="30" min="1"
                                            class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5 mb-6">
                                    <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Días disponibles</label>
                                    <select name="dias" class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                        <option>Lunes a Viernes</option>
                                        <option>Lunes a Domingo</option>
                                        <option>Martes a Domingo</option>
                                        <option>Fines de semana</option>
                                    </select>
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="horarioModal = false"
                                        class="border border-[#C5BBB0]/40 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#1A1612] hover:border-[#C9A84C] transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="bg-[#C9A84C] hover:bg-[#E8D08A] text-[#1A1612] px-5 py-2 text-[11px] font-medium uppercase tracking-[0.15em] transition-colors">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ── MODAL: CONFIRMAR ELIMINACIÓN ── --}}
                <div x-show="confirmModal" x-cloak
                    class="fixed inset-0 bg-[#1A1612]/70 z-50 flex items-center justify-center"
                    @click.self="confirmModal = false">
                    <div class="bg-white border border-[#C9A84C]/30 w-full max-w-sm overflow-hidden">
                        <div class="bg-[#1A1612] px-6 py-4 flex items-center justify-between">
                            <p class="font-serif text-lg text-[#C9A84C]">Confirmar eliminación</p>
                            <button @click="confirmModal = false" class="text-[#8C8070] hover:text-[#C9A84C] text-xl leading-none transition-colors">✕</button>
                        </div>
                        <div class="p-6 text-center">
                            <div class="text-4xl mb-4">⚠️</div>
                            <p class="font-serif text-lg text-[#1A1612] mb-2">¿Eliminar este registro?</p>
                            <p class="text-sm text-[#8C8070] mb-6">Esta acción no se puede deshacer.</p>
                            <div class="flex justify-center gap-3">
                                <button @click="confirmModal = false"
                                    class="border border-[#C5BBB0]/40 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#1A1612] hover:border-[#C9A84C] transition-colors">
                                    Cancelar
                                </button>
                                <form method="POST" :action="deleteType === 'expo' ? `/admin/exposiciones/${deleteTarget}` : `/admin/horarios/${deleteTarget}`">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="border border-[#8B2020]/30 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#8B2020] hover:bg-[#8B2020] hover:text-white hover:border-[#8B2020] transition-colors">
                                        Sí, eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- fin Alpine scope --}}
        </div>
    </div>
</x-app-layout>