@extends('layouts.app')

@section('content')
<div class="container">
    <div tole="tabpanel" class="row">
        <aside class="col-md-3">
            <div class="nav-settings">
                <h5 class="nav-heading">Configurações</h5>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('users.account') }}" class="nav-link {{ active('users.account') }}">
                            <i class="fas fa-fw fa-user-circle mr-2"></i>
                            Minha Conta
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="col-md-9">
            <div class="tab-content">
                <div id="account" class="tab-pane fade active show">
                    <div id="profile-form">
                        <profile-form :user="{{ Auth::user() }}" />
                    </div>

                    <div id="password-form">
                        <password-form :user="{{ Auth::user() }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
