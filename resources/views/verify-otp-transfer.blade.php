@extends('layouts.app')

@section('content')

<div class="container mt-5">
  <div class="row justify-content-center mt-5">
      <div class="col-md-3 justify-content-center mt-5">
        <form method="post" action="finalize-transfer" class="border border-info p-3 mt-5">
            @csrf
            <div class="form-group">
              <small class="text-danger"> Enter OTP</small><br/>
              <input type="hidden" value="{{$transfer_code}}" name="transfer-code" />
              <input type="text" class="form-control" name='otp' class="form-group" required maxlength="6" />
            </div>
            <hr class="my-2">
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-3 justify-content-center mt-3">
      <img class="my-3" src="img/paystack_logo_1.png" width="150px" height="25px"/>
    </div>
  </div>
</div>

@endsection
