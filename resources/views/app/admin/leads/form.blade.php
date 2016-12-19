@extends('templates.master')

@section('title')
    <title>WasteMaster | New Hauler</title>
@stop

@section('content')
    <div class="row">

        <!-- Details Column -->
        <div class="col-sm-5">
            @if(! $editMode)
                <h2>Create New Lead</h2>
            @else
                <h2>Update {{ $lead->name }}</h2>
            @endif

                <br>

            @if(! $editMode)
                <form action="{{ route('leads::create') }}" method="post" class="form-horizontal">
            @else
                <form action="{{ route('leads::update', ['id' => $lead->id]) }}" method="post" class="form-horizontal">
            @endif

                {{ csrf_field() }}

                @if ($editMode)
                    <!-- Time Created -->
                    <div class="form-group">
                        <label for="name" class="control-label col-sm-4">Time Created</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="created_at" value="{{ $lead->created_at }}" disabled />
                        </div>
                    </div>
                @endif

                <!-- Company Name -->
                <div class="form-group">
                    <label for="name" class="control-label col-sm-4">Business Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="company" value="{{ $lead->company or old('company') }}" autofocus required />
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address" class="control-label col-sm-4">Address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="address" value="{{ $lead->address or old('address') }}" required />
                    </div>
                </div>

                <!-- City/State -->
                <div class="form-group">
                    <label for="city" class="control-label col-sm-4">City:</label>
                    <div class="col-sm-8">
                        <input class="typeahead form-control" name="city" value="{{ $lead->city->name or old('city') }}" required>
                    </div>
                </div>

                <!-- Contact Name -->
                <div class="form-group">
                    <label for="contact_name" class="control-label col-sm-4">Contact Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="contact_name" value="{{ $lead->contact_name or old('contact_name') }}" required />
                    </div>
                </div>

                <!-- Contact Email -->
                <div class="form-group">
                    <label for="contact_email" class="control-label col-sm-4">Contact Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="contact_email" value="{{ $lead->contact_email or old('contact_email') }}" required />
                    </div>
                </div>

                <!-- Account Number -->
                <div class="form-group">
                    <label for="account_num" class="control-label col-sm-4">Account Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="account_num" value="{{ $lead->account_num or old('account_num') }}" required />
                    </div>
                </div>

                <!-- Current Hauler -->
                <div class="form-group">
                    <label for="hauler_id" class="control-label col-sm-4">Current Hauler</label>
                    <div class="col-sm-8">
                        <select name="hauler_id" class="form-control">
                            <option value="0">Select a Hauler...</option>
                        @if ($haulers)
                            @foreach ($haulers as $hauler)
                                    <option value="{{ $hauler->id }}" @if (isset($lead) && $lead->hauler_id == $hauler->id) selected @endif>
                                        {{ $hauler->name }}
                                    </option>
                            @endforeach
                        @endif
                        </select>
                    </div>
                </div>

                <!-- Services -->
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center">#</th>
                            <th class="text-center">yd</th>
                            <th class="text-center">#/wk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Waste -->
                        <tr>
                            <td>
                                <div>
                                    <label class="form-inline control-label">MSW</label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="msw_qty" class="form-control" value="{{ $lead->msw_qty or old('msw_qty') }}">
                            </td>
                            <td>
                                <input type="text" name="msw_yards" class="form-control" value="{{ $lead->msw_yards or old('msw_yards') }}">
                            </td>
                            <td>
                                <input type="text" name="msw_per_week" class="form-control" value="{{ $lead->msw_per_week or old('msw_per_week') }}">
                            </td>
                        </tr>
                        <!-- Recycling -->
                        <tr>
                            <td>
                                <div>
                                    <label class="form-inline control-label">REC</label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="rec_qty" class="form-control" value="{{ $lead->rec_qty or old('rec_qty') }}">
                            </td>
                            <td>
                                <input type="text" name="rec_yards" class="form-control" value="{{ $lead->rec_yards or old('rec_yards') }}">
                            </td>
                            <td>
                                <input type="text" name="rec_per_week" class="form-control" value="{{ $lead->rec_per_week or old('rec_per_week') }}">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Total Monthly -->
                <div class="form-group">
                    <label for="monthly_price" class="control-label col-sm-4">Total Monthly</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-addon">$</div>
                            <input type="text" class="form-control" name="monthly_price" value="{{ $lead->monthly_price or old('monthly_price') }}" required />
                        </div>
                    </div>
                </div>

                <br>

                <div class="text-center">
                    @if(request()->is('admin/hauler'))
                        <input type="submit" class="btn btn-success" value="Create Lead">
                    @else
                        <input type="submit" class="btn btn-success" value="Save Lead">
                    @endif
                </div>

            </form>
        </div>


        <div class="col-sm-5 col-sm-offset-1">

            @include('app.admin.leads._send_emails')

        </div>
    </div>

@endsection

@section('scripts')
    @include('app.admin.shared._city_script')
@endsection
