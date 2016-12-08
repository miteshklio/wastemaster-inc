@extends('templates.master')

@section('title')
    <title>WasteMaster | Users</title>
@stop

@section('content')
    <h2>Manage Users</h2>

    <div class="row">
        <div class="col-sm-6">
             <a href="/admin/user" class="btn btn-sm btn-success">New User</a>
        </div>
        <div class="col-sm-6 text-right">
            {!! $datatable->renderSearch() !!}
        </div>
    </div>

    <br>

    @if ($datatable->hasResults())

        <p>{!! $datatable->renderMeta() !!}</p>

        <table class="table">
            {!! $datatable->renderHeader('table') !!}
            <tbody>
            @foreach ($datatable->rows() as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <a href="/admin/user/{{ $user->id }}">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>{{ date('Y-m-d', strtotime($user->created_at)) }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Modify <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/user/{{ $user->id }}">Edit</a></li>
                                <li><a href="/admin/user/{{ $user->id }}/delete">Delete</a></li>
                            </ul>                        
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $datatable->renderLinks() !!}
    @else
        <div class="alert alert-warning">
            No users were found in the system.
        </div>
    @endif
@endsection
