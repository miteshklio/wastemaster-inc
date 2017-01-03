@extends('templates.master')

@section('title')
    <title>WasteMaster | Bid A Project</title>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h2 class="text-center">Submit a bid for {{ $lead->company }}</h2>

            <p class="text-center">{{ $lead->address }} {{ $lead->city->name }}</p>

            @if ($bid)
                <div class="row">
                    <div class="alert alert-info col-sm-11">
                        A bid from your company already exists for {{ $lead->company }}.
                    </div>
                </div>
            @endif

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
                                <input type="email" class="form-control" name="hauler_email" value="{{ old('hauler_email', ! empty($bid->hauler_email) ? $bid->hauler_email : null ) }}">
                            </div>
                        </div>
                    </div>
                </div>





                <br><br>

                <p class="text-center">What are the best rates you can offer for the following services?</p>

                <div class="row">
                    @if (! empty($lead->msw_qty))
                    <div class="col-sm-5">

                        <div class="form-group">
                            <label for="msw_price" class="control-label">Monthly MSW Price</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="msw_price" id="msw_price" class="form-control fee" value="{{ old('msw_price', ! empty($bid->msw_price) ? $bid->msw_price : null ) }}">
                            </div>
                        </div>

                        <p class="small text-center">
                            {{ $lead->msw_qty }} dumpsters, {{ $lead->msw_yards }} yds, {{ $lead->msw_per_week }}/week
                        </p>

                    </div>
                    @endif

                    @if (! empty($lead->rec_qty))
                    <div class="col-sm-5 @if (! empty($lead->msw_qty)) col-sm-offset-1 @endif">

                        <div class="form-group">
                            <label for="rec_price" class="control-label">Monthly REC Price</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="rec_price" id="rec_price" class="form-control fee" value="{{ old('rec_price', ! empty($bid->rec_price) ? $bid->rec_price : null ) }}">
                            </div>
                        </div>

                        <p class="small text-center">
                            {{ $lead->rec_qty }} dumpsters, {{ $lead->rec_yards }} yds, {{ $lead->rec_per_week }}/week
                        </p>

                    </div>
                    @endif
                </div>




                <br><br>

                <p class="text-center">Have any additional fees? Leave fields blank that don't apply.</p>

                <div class="row">
                    <div class="col-sm-3">

                        <div class="form-group">
                            <label for="rec_offset" class="control-label">REC Offset Fee</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="rec_offset" id="rec_offset" class="form-control fee" value="{{ old('rec_offset', ! empty($bid->rec_offset) ? $bid->rec_offset : null ) }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-3 col-sm-offset-1">

                        <div class="form-group">
                            <label for="fuel_surcharge" class="control-label">Fuel Surcharge</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="fuel_surcharge" id="fuel_surcharge" class="form-control fee" value="{{ old('fuel_surcharge', ! empty($bid->fuel_surcharge) ? $bid->fuel_surcharge : null ) }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-3 col-sm-offset-1">

                        <div class="form-group">
                            <label for="env_surcharge" class="control-label">Enviro Surcharge</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="env_surcharge" id="env_surcharge" class="form-control fee" value="{{ old('env_surcharge', ! empty($bid->env_surcharge) ? $bid->env_surcharge : null ) }}">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">

                        <div class="form-group">
                            <label for="recovery_fee" class="control-label">Regulatory Cost Recovery</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="recovery_fee" id="recovery_fee" class="form-control fee" value="{{ old('recovery_fee', ! empty($bid->recovery_fee) ? $bid->recovery_fee : null ) }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-3 col-sm-offset-1">

                        <div class="form-group">
                            <label for="admin_fee" class="control-label">Admin Fee</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="admin_fee" id="admin_fee" class="form-control fee" value="{{ old('admin_fee', ! empty($bid->admin_fee) ? $bid->admin_fee : null ) }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-3 col-sm-offset-1">

                        <div class="form-group">
                            <label for="other_fees" class="control-label">Other Fees (lock, etc)</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="other_fees" id="other_fees" class="form-control fee" value="{{ old('other_fees', ! empty($bid->other_fees) ? $bid->other_fees : null ) }}">
                            </div>
                        </div>

                    </div>
                </div>

                <br>

                <p class="text-center">Please make sure the total monthly calculation is correct, and add any additional notes.</p>

                <div class="row">
                    <div class="col-sm-3">

                        <div class="form-group">
                            <label for="net_monthly" class="control-label">Total Monthly</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" name="net_monthly" id="total" class="form-control" value="{{ old('net_monthly', ! empty($bid->net_monthly) ? $bid->net_monthly : null ) }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-7 col-sm-offset-1">

                        <div class="form-group">
                            <label for="notes" class="control-label">Notes</label>
                            <textarea name="notes" rows="6" class="form-control">{{ old('notes', ! empty($bid->notes) ? $bid->notes : null ) }}</textarea>
                        </div>

                    </div>
                </div>

                <br>

                @if (empty($bid))
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <input type="submit" class="btn btn-success btn-lg" value="Submit Bid!">
                    </div>
                </div>
                @endif

            </form>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Calculates the Net monthly costs and updates the form.
        $('.fee').change(function(){
            var mswPrice = parseFloat($('#msw_price').val());
            var recPrice = parseFloat($('#rec_price').val());
            var recOffset = parseFloat($('#rec_offset').val());
            var fuelSurcharge = parseFloat($('#fuel_surcharge').val());
            var envSurcharge = parseFloat($('#env_surcharge').val());
            var recovery = parseFloat($('#recovery_fee').val());
            var admin = parseFloat($('#admin_fee').val());
            var other = parseFloat($('#other_fees').val());

            var net =
                (mswPrice ? mswPrice : 0)+
                (recPrice ? recPrice : 0) +
                (recOffset ? recOffset : 0) +
                (fuelSurcharge ? fuelSurcharge : 0) +
                (envSurcharge ? envSurcharge : 0) +
                (recovery ? recovery : 0) +
                (admin ? admin : 0) +
                (other ? other : 0);

            $('#total').val(net.toFixed(2)).trigger('change');
        });

        // Format the fee and update the Net Value
        $('.fee, .number').change(function(){
            var amount = parseFloat($(this).val())

            $(this).val(amount ? amount.toFixed(2) : 0.00);
        });
    </script>
@endsection
