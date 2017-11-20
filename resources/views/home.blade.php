@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @forelse (auth()->user()->teams as $team)
                        <h2>Your teams:</h2>
                        <li><a href="{{ $team->path() }}">{{ $team->name }}</a></li>
                    @empty
                        You're currently in no teams.
                    @endforelse

                    @include ('teams.forms.create')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
