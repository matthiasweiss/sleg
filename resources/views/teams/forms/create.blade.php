<h3>Create a new team:</h3>
<form action="/teams" method="POST">
    {{ csrf_field() }}
    <div class="col-xs-4">
        <input type="text" name="name" class="form-control mb-1" placeholder="Name of your team">
        <button class="btn btn-primary form-control">Create Team</button>
    </div>
</form>