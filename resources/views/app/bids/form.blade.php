@extends('templates.master')

@section('title')
    <title>WasteMaster | Bid A Project</title>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h2 class="text-center">Submit a bid for {{ $lead->company }}</h2>

            <p class="text-center">{{ $lead->address }} {{ $lead->city->name }}</p>

            <form action="{{ route('bids::submitBid', ['id' => $code]) }}" method="post" class="form-horizontal">


                <div class="row">
                    <div class="col-sm-5">

                        <div class="form-group">
                            <label class="control-label">Your Company</label>
                            <input type="text" name="hauler" class="form-control" value="{{ $hauler->name }}" disabled>
                        </div>

                    </div>
                    <div class="col-sm-5 col-sm-offset-1">

                        <div class="form-group">
                            <label for="hauler_email" class="control-label">Your Email</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                <input type="email" class="form-control" name="hauler_email" value="{{ old('hauler_email') }}">
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
