@extends ('layouts.app')

@section ('content')
    <div class="container">
        <div class="panel container">
            <div class="panel-header"><h1>{{ $team->name }}</h1></div>
            <hr>
            <div class="panel-body">
                <h2>Members:</h2>
                @forelse ($team->members as $member)
                    <li>{{ $member->name }} {{ $member->owns($team) ? "(Owner)" : "" }}</li>
                @empty
                    There are no other members yet.
                @endforelse
                <hr>

                <add-members-to-team-form :team="{{ $team }}"></add-user-to-team-form>
            </div>
        </div>
    </div>


@endsection