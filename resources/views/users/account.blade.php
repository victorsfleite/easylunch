@extends('layouts.app')

@section('content')
<div class="container">
    <div tole="tabpanel" class="row">
        <div class="col-md-3">
            <div class="card card-default card-flush">
                <div class="card-header">
                    Configurações
                </div>

                <div role="tablist" class="nav flex-column nav-tabs inner-nav stacked">
                    <a data-toggle="tab" aria-controls="account" href="#account" role="tab" class="nav-link active show" aria-selected="true">
                        <i class="fas fa-fw fa-cogs"></i>
                        Minha Conta
                    </a>
                </div>
            </div>
        </div>

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