<x-admin-master>
    @section('content')
    <div class="row">
        @if(session()->has('role-deleted'))
        <div class="alert alert-danger">
            {{ session('role-deleted') }}
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-3">
            <form action="{{ route('roles.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                    <div>
                        @error('name')
                        <span><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create</button>
            </form>
        </div>
        <div class="col-sm-9">
            <div class="card shadow mb 4">
                <div class="card-header py-3">
                    <h6 class="m-0">Roles</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Delete</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td><a href="{{ route('roles.edit', $role->id) }}">{{ $role->name }}</a></td>
                                    <td>{{ $role->slug }}</td>
                                    <td>
                                        <form method="post" action="{{ route('roles.destroy', $role->id) }}">
                                            @csrf
                                            @method("DELETE")

                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-admin-master>
