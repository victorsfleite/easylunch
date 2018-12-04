<li class="nav-item">
    <a href="{{ route('menus') }}" class="nav-link {{ active('menus') }}">Menus</a>
</li>







@if (auth()->check() && auth()->user()->is_admin)
    <li class="nav-item">
        <a href="{{ route('menus.report') }}" class="nav-link {{ active('menus.report') }}">Relatório de Pedidos</a>
    </li>
@endif
