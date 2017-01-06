@extends('templates.master')

@section('title')
    <title>Wastemaster | Haulers</title>
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
                <tr @if ($row->archived) class="archived" @endif>
                    <td>
                        <a href="{{ route('haulers::show', ['id' => $row->id]) }}">{{ $row->name }}</a>
                    </td>
                    <td>{{ $row->city->name }}</td>
                    <td>{{ $row->listWasteTypes() }}</td>
                    <td>{{ $row->listEmails() }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Modify <span class="caret"></span>
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('haulers::show', ['id' => $row->id]) }}">Details</a>
                                </li>
                                <li>
                                    @if ($row->archived)
                                        <a href="{{ route('haulers::unarchive', ['id' => $row->id]) }}">
                                            UN-Archive
                                        </a>
                                    @else
                                        <a href="{{ route('haulers::archive', ['id' => $row->id]) }}" onClick="return confirm('Archive this Hauler?');">
                                            Archive
                                        </a>
                                    @endif
                                </li>
                                <li>
                                    <a href="{{ route('haulers::delete', ['id' => $row->id]) }}" onClick="return confirm('Delete this Hauler permanently?');">
                                        Delete
                                    </a>
                                </li>
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
