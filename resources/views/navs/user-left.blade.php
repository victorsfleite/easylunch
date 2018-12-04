<li class="nav-item">
    <a href="{{ route('menus') }}" class="nav-link {{ active('menus') }}">Menus</a>
</li>







@if (Auth::check() && (Auth::user()->is_admin || Auth::user()->is_chef))
    <li class="nav-item">
        <a href="{{ route('menus.report') }}" class="nav-link {{ active('menus.report') }}">Relatório de Pedidos</a>
    </li>
@endif
