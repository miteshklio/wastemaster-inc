@extends('templates.master')

@section('title')
    <title>WasteMaster | Haulers</title>
@stop

@section('content')
    <h2>Manage Haulers</h2>

    <div class="row">
        <div class="col-sm-6">
            {!! $datatable->renderSearch() !!}
        </div>
        <div class="col-sm-6 text-right">
             <a href="{{ route('haulers::new') }}" class="btn btn-sm btn-success">New Hauler</a>
        </div>
    </div>

    <br>

    @if ($datatable->hasResults())

        <p>{!! $datatable->renderMeta() !!}</p>

        <table class="table">
            {!! $datatable->renderHeader('table') !!}
            <tbody>
            @foreach ($datatable->rows() as $row)
                <tr>
                    <td>
                        <a href="">{{ $row->name }}</a>
                    </td>
                    <td>{{ $row->city_id }}</td>
                    <td>{{ $row->listWasteTypes() }}</td>
                    <td>{{ $row->listEmails() }}</td>
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
