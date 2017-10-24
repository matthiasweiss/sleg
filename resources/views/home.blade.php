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
                    <hr>
                    <h3>Create a new team:</h3>
                    <form action="/teams" method="POST">
                        {{ csrf_field() }}
                        <input type="text" name="name" class="form-control" placeholder="Name of your team"><br>
                        <button class="btn btn-primary form-control">Create Team</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
