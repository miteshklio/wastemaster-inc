@extends('templates.master')

@section('title')
    <title>WasteMaster | Bids</title>
@stop

@section('content')
    <h2>Manage Bids</h2>

    <div class="row">
        <div class="col-sm-12">
            {!! $datatable->renderSearch() !!}
        </div>
    </div>

    <br>

    @if ($datatable->hasResults())

        <p>{!! $datatable->renderMeta() !!}</p>

        <table class="table">
            {!! $datatable->renderHeader('table') !!}
            <tbody>
            @foreach ($datatable->rows() as $row)
                <tr @if ($row->status == \App\Bid::STATUS_CLOSED) class="archived" @endif>
                    <td>
                        <a href="{{ route('clients::show', ['id' => $row->id]) }}">{{ $row->lead_name }}</a>
                    </td>
                    <td>{{ $row->describeStatus() }}</td>
                    <td>{{ $row->hauler_name }}</td>
                    <td>{{ date('M D, Y', strtotime($row->created_at)) }}</td>
                    <td>${{ number_format($row->current_total, 2) }}</td>
                    <td>${{ number_format($row->net_monthly, 2) }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Modify <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('bids::show', ['id' => $row->id]) }}">Details</a>
                                </li>
                                <li>
                                @if ($row->status == \App\Bid::STATUS_LIVE)
                                    <a href="{{ route('bids::accept', ['id' => $row->id]) }}" onClick="return confirm('Accept this bid and close others for this lead?')">Accept</a>
                                @elseif ($row->status == \App\Bid::STATUS_ACCEPTED)
                                    <a href="{{ route('bids::rescind', ['id' => $row->id]) }}" onClick="return confirm('Rescind this bid?');">
                                        Rescind
                                    </a>
                                @endif
                                </li>
                                <li>
                                    <a href="{{ route('bids::delete', ['id' => $row->id]) }}" onClick="return confirm('Delete this Bid permanently?');">
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