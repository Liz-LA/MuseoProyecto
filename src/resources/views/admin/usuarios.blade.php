<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-[#8C8070] hover:text-[#C9A84C] transition-colors text-sm uppercase tracking-widest">← Admin</a>
                <span class="text-[#C5BBB0]">/</span>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 border border-[#C9A84C] flex items-center justify-center text-[#C9A84C] text-xs">👤</div>
                    <h2 class="font-serif text-2xl text-[#1A1612] font-normal">
                        Administración de <em class="italic text-[#C9A84C]">Usuarios</em>
                    </h2>
                </div>
            </div>
            <button onclick="document.getElementById('modal-nuevo-usuario').classList.add('open')"
                class="bg-[#C9A84C] hover:bg-[#E8D08A] text-[#1A1612] px-5 py-2 text-[11px] font-medium uppercase tracking-[0.15em] transition-colors">
                + Agregar usuario
            </button>
        </div>
    </x-slot>

    <div class="py-10 bg-[#F5F1EC] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros --}}
            <div class="flex flex-col sm:flex-row gap-3 mb-8">
                <input type="text" placeholder="Buscar por nombre o correo…"
                    class="border border-[#C5BBB0]/50 bg-white px-4 py-2 text-sm text-[#1A1612] placeholder-[#8C8070] focus:outline-none focus:border-[#C9A84C] flex-1 rounded-none">
                <select class="border border-[#C5BBB0]/50 bg-white px-4 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                    <option value="">Todos los roles</option>
                    <option value="admin">Administrador</option>
                    <option value="user">Visitante</option>
                </select>
                {{-- <select class="border border-[#C5BBB0]/50 bg-white px-4 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                    <option value="">Todos los estados</option>
                    <option value="active">Activo</option>
                    <option value="inactive">Inactivo</option>
                </select>--}}
            </div>

            {{-- Tabla de usuarios --}}
            <div class="bg-white border border-[#C5BBB0]/30 overflow-hidden mb-8">
                <div class="bg-[#1A1612] px-6 py-4 flex justify-between items-center">
                    <p class="font-serif text-base text-[#C9A84C]">Usuarios registrados</p>
                    <span class="text-[11px] text-[#8C8070] uppercase tracking-widest">152 en total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-[#C5BBB0]/20">
                                <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Nombre</th>
                                <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Correo</th>
                                <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Rol</th>
                                <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Reservas</th>
                                {{--<th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Estado</th>--}}
                                <th class="text-left text-[10px] uppercase tracking-[0.15em] text-[#8C8070] px-5 py-3 font-normal">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios ?? [] as $usuario)
                            <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03] transition-colors">
                                <td class="px-5 py-3 text-[13px] text-[#1A1612] font-medium">{{ $usuario->name }}</td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $usuario->email }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em]
                                        {{ $usuario->role === 'admin' ? 'bg-[#1A1612] text-[#C9A84C]' : 'bg-[#F0EDE8] text-[#6B6257]' }}">
                                        {{ $usuario->role === 'admin' ? 'Administrador' : 'Visitante' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">{{ $usuario->reservas_count ?? 0 }}</td>
                                {{--<td class="px-5 py-3">
                                    <span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em]
                                        {{ $usuario->active ? 'bg-[#E8F4EE] text-[#1A6B3C]' : 'bg-[#FAEAEA] text-[#8B2020]' }}">
                                        {{ $usuario->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>--}}
                                <td class="px-5 py-3">
                                    <div class="flex gap-2">
                                        <button onclick="editarUsuario({{ $usuario->id }})"
                                            class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#1A1612] hover:border-[#C9A84C] hover:text-[#C9A84C] transition-colors">
                                            Editar
                                        </button>
                                        <button onclick="confirmarEliminar({{ $usuario->id }}, '{{ $usuario->name }}')"
                                            class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#8B2020] hover:border-[#8B2020] hover:bg-[#8B2020] hover:text-white transition-colors">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            {{-- Datos de muestra --}}
                            <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03]">
                                <td class="px-5 py-3 text-[13px] text-[#1A1612] font-medium">Ana García López</td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">ana.garcia@correo.com</td>
                                <td class="px-5 py-3"><span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#F0EDE8] text-[#6B6257]">Visitante</span></td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">4</td>
                                {{-- <td class="px-5 py-3"><span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#E8F4EE] text-[#1A6B3C]">Activo</span></td> --}}
                                <td class="px-5 py-3">
                                    <div class="flex gap-2">
                                        <button class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#1A1612] hover:border-[#C9A84C] hover:text-[#C9A84C] transition-colors">Editar</button>
                                        <button class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#8B2020] hover:border-[#8B2020] hover:bg-[#8B2020] hover:text-white transition-colors">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03]">
                                <td class="px-5 py-3 text-[13px] text-[#1A1612] font-medium">Carlos Mendoza</td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">carlos.m@correo.com</td>
                                <td class="px-5 py-3"><span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#F0EDE8] text-[#6B6257]">Visitante</span></td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">7</td>
{{--                                 <td class="px-5 py-3"><span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#E8F4EE] text-[#1A6B3C]">Activo</span></td>--}}
                                <td class="px-5 py-3">
                                    <div class="flex gap-2">
                                        <button class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#1A1612] hover:border-[#C9A84C] hover:text-[#C9A84C] transition-colors">Editar</button>
                                        <button class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#8B2020] hover:border-[#8B2020] hover:bg-[#8B2020] hover:text-white transition-colors">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-[#C5BBB0]/10 hover:bg-[#C9A84C]/[0.03]">
                                <td class="px-5 py-3 text-[13px] text-[#1A1612] font-medium">Admin Museo</td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">admin@museo.com</td>
                                <td class="px-5 py-3"><span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#1A1612] text-[#C9A84C]">Administrador</span></td>
                                <td class="px-5 py-3 text-[13px] text-[#8C8070]">—</td>
                                {{-- <td class="px-5 py-3"><span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-[0.1em] bg-[#E8F4EE] text-[#1A6B3C]">Activo</span></td> --}}
                                <td class="px-5 py-3">
                                    <div class="flex gap-2">
                                        <button class="border border-[#C5BBB0]/40 px-3 py-1 text-[10px] uppercase tracking-[0.1em] text-[#1A1612] hover:border-[#C9A84C] hover:text-[#C9A84C] transition-colors">Editar</button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginación --}}
                @if(isset($usuarios) && $usuarios->hasPages())
                <div class="px-6 py-4 border-t border-[#C5BBB0]/15">
                    {{ $usuarios->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal: Nuevo / Editar Usuario --}}
    <div id="modal-nuevo-usuario" class="fixed inset-0 bg-[#1A1612]/70 z-50 hidden items-center justify-center"
        x-data x-on:click.self="document.getElementById('modal-nuevo-usuario').classList.remove('open')">
        <div class="bg-white border border-[#C9A84C]/30 w-full max-w-lg overflow-hidden">
            <div class="bg-[#1A1612] px-6 py-4 flex items-center justify-between">
                <p class="font-serif text-lg text-[#C9A84C]">Nuevo Usuario</p>
                <button onclick="document.getElementById('modal-nuevo-usuario').classList.remove('open')"
                    class="text-[#8C8070] hover:text-[#C9A84C] text-xl leading-none transition-colors">✕</button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.usuarios.store') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Nombre</label>
                            <input type="text" name="name" placeholder="Ana"
                                class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Apellidos</label>
                            <input type="text" name="last_name" placeholder="García López"
                                class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5 mb-4">
                        <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Correo electrónico</label>
                        <input type="email" name="email" placeholder="ana@correo.com"
                            class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Contraseña</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] uppercase tracking-[0.15em] text-[#8C8070]">Rol</label>
                            <select name="role"
                                class="border border-[#C5BBB0]/40 bg-[#FAF8F4] px-3 py-2 text-sm text-[#1A1612] focus:outline-none focus:border-[#C9A84C] rounded-none">
                                <option value="user">Visitante</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-nuevo-usuario').classList.remove('open')"
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

    {{-- Modal: Confirmar eliminación --}}
    <div id="modal-eliminar-usuario" class="fixed inset-0 bg-[#1A1612]/70 z-50 hidden items-center justify-center">
        <div class="bg-white border border-[#C9A84C]/30 w-full max-w-md overflow-hidden">
            <div class="bg-[#1A1612] px-6 py-4 flex items-center justify-between">
                <p class="font-serif text-lg text-[#C9A84C]">Eliminar Usuario</p>
                <button onclick="document.getElementById('modal-eliminar-usuario').classList.add('hidden'); document.getElementById('modal-eliminar-usuario').classList.remove('flex')"
                    class="text-[#8C8070] hover:text-[#C9A84C] text-xl leading-none transition-colors">✕</button>
            </div>
            <div class="p-6 text-center">
                <div class="text-4xl mb-4">⚠️</div>
                <p class="font-serif text-xl text-[#1A1612] mb-2">¿Eliminar a <span id="eliminar-nombre" class="italic text-[#C9A84C]"></span>?</p>
                <p class="text-sm text-[#8C8070] mb-6">Esta acción no se puede deshacer. Se eliminarán también sus reservas asociadas.</p>
                <div class="flex justify-center gap-3">
                    <button onclick="document.getElementById('modal-eliminar-usuario').classList.add('hidden')"
                        class="border border-[#C5BBB0]/40 px-5 py-2 text-[11px] uppercase tracking-[0.15em] text-[#1A1612] hover:border-[#C9A84C] transition-colors">
                        Cancelar
                    </button>
                    <form id="form-eliminar-usuario" method="POST">
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

    <script>
        // Abrir modal con clase open (compatible con diseño original)
        document.querySelectorAll('[id^="modal-"]').forEach(m => {
            new MutationObserver(() => {
                if (m.classList.contains('open')) {
                    m.classList.remove('hidden');
                    m.classList.add('flex');
                } else {
                    m.classList.add('hidden');
                    m.classList.remove('flex');
                }
            }).observe(m, { attributes: true, attributeFilter: ['class'] });
        });

        function confirmarEliminar(id, nombre) {
            document.getElementById('eliminar-nombre').textContent = nombre;
            document.getElementById('form-eliminar-usuario').action = `/admin/usuarios/${id}`;
            document.getElementById('modal-eliminar-usuario').classList.remove('hidden');
            document.getElementById('modal-eliminar-usuario').classList.add('flex');
        }

        function editarUsuario(id) {
            window.location.href = `/admin/usuarios/${id}/editar`;
        }
    </script>
</x-app-layout>