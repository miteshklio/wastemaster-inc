@extends('templates.master')

@section('title')
    <title>WasteMaster | Leads</title>
@stop

@section('content')
    <h2>Manage Leads</h2>

    <div class="row">
        <div class="col-sm-6">
            {!! $datatable->renderSearch() !!}
        </div>
        <div class="col-sm-6 text-right">
             <a href="{{ route('leads::new') }}" class="btn btn-sm btn-success">New Lead</a>
        </div>
    </div>

    <br>

    @if ($datatable->hasResults())

        <p>{!! $datatable->renderMeta() !!}</p>

        <table class="table">
            {!! $datatable->renderHeader('table') !!}
            <tbody>
            @foreach ($datatable->rows() as $row)

                <tr class="@if ($row->archived) archived @endif @if ($bids->leadHasRecent($row->id, $recentDate)) has_bids @endif">
                    <td>
                        <a href="{{ route('leads::show', ['id' => $row->id]) }}">{{ $row->company }}</a>
                    </td>
                    <td>{{ $row->city->name }}</td>
                    <td>{{ date('M j, Y', strtotime($row->created_at)) }}</td>
                    <td>{{ $row->status }}</td>
                    <td>${{ number_format($row->monthly_price, 0) }}</td>
                    <td>{{ $row->cheapestBid() }}</td>
                    <td>{{ $row->bid_count }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Modify <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('leads::show', ['id' => $row->id]) }}">Details</a>
                                </li>
                                <li>
                                @if ($row->archived)
                                    <a href="{{ route('leads::unarchive', ['id' => $row->id]) }}">UN-Archive</a>
                                @else
                                    <a href="{{ route('leads::archive', ['id' => $row->id]) }}" onClick="return confirm('Archive this Lead?');">
                                        Archive
                                    </a>
                                @endif
                                </li>
                                <li>
                                    <a href="{{ route('leads::delete', ['id' => $row->id]) }}" onClick="return confirm('Delete this Lead permanently?');">
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td>

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
