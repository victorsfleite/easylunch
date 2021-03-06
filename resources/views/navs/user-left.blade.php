<li class="nav-item">
    <a href="{{ route('menus') }}" class="nav-link {{ active('menus') }}">Menus</a>
</li>

<li class="nav-item">
    <a href="{{ route('menus.report') }}" class="nav-link {{ active('menus.report') }}">Relatório de Pedidos</a>
</li>

@if (Auth::user()->is_admin)
    <li class="nav-item">
        <a href="{{ route('users') }}" class="nav-link {{ active('users') }}">Usuários</a>
    </li>
@endif
