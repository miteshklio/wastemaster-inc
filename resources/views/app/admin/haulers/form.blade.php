@extends('templates.master')

@section('title')
    <title>WasteMaster | New Hauler</title>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            @if(request()->is('admin/hauler'))
                <h2>Create New User</h2>
            @else
                <h2>Update {{ $hauler->name }}</h2>
            @endif

            <form action="{{ route('haulers::create') }}" method="post" class="">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $hauler->name or old('name') }}" autofocus required />
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" class="form-control" name="city" value="{{ $hauler->city or old('city') }}" required />
                </div>

                <div class="row">
                    <div class="col-sm-3 col-xs-12">
                        <div class="checkbox">
                            <label for="recycle">
                                <input type="checkbox" name="recycle"> REC
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="checkbox">
                            <label for="waste">
                                <input type="checkbox" name="waste"> MSW
                            </label>
                        </div>
                    </div>
                </div>

                <br>

                <div class="form-group">
                    <label for="emails">Emails</label>
                    <input type="text" name="emails" class="form-control" value="{{ $hauler->emails or old('emails') }}">
                    <p class="small">Separate multiple addresses by a comma.</p>
                </div>

                <br>

                <div class="text-center">

                </div>

            </form>
        </div>
    </div>

@endsection
