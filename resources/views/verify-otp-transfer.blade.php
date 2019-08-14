@extends('layouts.app')

@section('content')

<div class="container mt-5">
  <div class="row justify-content-center mt-5">
      <div class="col-md-3 justify-content-center mt-5">

        <div class="text-center"><?php if(isset($msg)){echo "<span class='text-center'> {$msg} </span>";}?></div>
        <form method="post" action="finalize-transfer" class="border border-info p-3 mt-5">
            @csrf
            <div class="form-group">
              <small class="text-danger text-center"> Enter OTP</small><br/>
              <input type="hidden" value="{{$transfer_code}}" name="transfer-code" />
              <input type="text" class="form-control" name='otp' class="form-group" required maxlength="6" />
            </div>
            <hr class="my-2">
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <a class="float-right mt-2" href="resend-otp?tcode={{$transfer_code}}"> RESEND OTP </a>
        </form>
      </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-3 justify-content-center text-center">
        <img src="https://farmkart.ng/wp-content/plugins/woo-paystack/assets/images/paystackwhite.png" width="150px" height="40px" />
    </div>
  </div>
</div>

@endsection
