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

                @if(request()->is('admin/hauler'))
                    <form action="{{ route('haulers::create') }}" method="post" class="">
                @else
                    <form action="{{ route('haulers::update', ['id' => $hauler->id]) }}" method="post" class="">
                @endif

                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $hauler->name or old('name') }}" autofocus required />
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input class="typeahead form-control" name="city" value="{{ $hauler->city->name or old('city') }}" required>
                </div>

                <div class="row">
                    <div class="col-sm-3 col-xs-12">
                        <div class="checkbox">
                            <label for="recycle">
                                <input type="checkbox" name="recycle" @if (old('recycle', $hauler->svc_recycle ?? null)) checked @endif> REC
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12">
                        <div class="checkbox">
                            <label for="waste">
                                <input type="checkbox" name="waste" @if (old('recycle', $hauler->svc_recycle ?? null)) checked @endif> MSW
                            </label>
                        </div>
                    </div>
                </div>

                <br>

                <div class="form-group">
                    <label for="emails">Emails</label>
                    <textarea name="emails"  rows="3" class="form-control">{{ isset($hauler) ? $hauler->listEmails() : old('emails') }}</textarea>
                    <p class="small">Separate multiple addresses by a comma.</p>
                </div>

                <br>

                <div class="text-center">
                    @if(request()->is('admin/hauler'))
                        <input type="submit" class="btn btn-success" value="Create Hauler">
                    @else
                        <input type="submit" class="btn btn-success" value="Save Hauler">
                    @endif
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var cities = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: ['Chicago', 'Minneapolis'],
            remote: {
                url: '/ajax/cities/autocomplete?query=%QUERY',
                wildcard: '%QUERY',
                filter: function (data) {
                    return $.map(data.cities, function (city) {
                        return {
                            value: city
                        };
                    });
                }
            }
        });

        $('.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1,
        },{
            name: 'cities',
            source: cities,
            display: 'value',
            limit: 100
        });
    </script>
@endsection
