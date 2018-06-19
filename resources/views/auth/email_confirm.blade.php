@extends('layouts.home')

@section('content')
<div class="container email-confirm-container custom-container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Потврда на емаил</div>

                <div class="panel-body">
                    {{-- @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif --}}

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <a href="{{route('login')}}" class="btn btn-primary">Продолжи кон најава</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
