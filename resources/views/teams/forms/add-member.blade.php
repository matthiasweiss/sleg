<h3 class="mb-1">Add a new member to your team:</h3>
<form action="/memberships" method="POST">
    {{ csrf_field() }}
    <div class="col-xs-4">
        <input type="hidden" name="user_id" value="">
        <input type="text" name="name" class="form-control mb-1" placeholder="Search for member">
        <button class="btn btn-primary form-control">Add member</button>
    </div>
</form>