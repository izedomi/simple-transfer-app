
@extends('layouts.app')
@section('content')


<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-3">
          <p class="text-center">Bulk Transfer Recipients ({{count($supplierDetails)}})<p>
    </div>
  </div>
  <div class="row justify-content-center">

    <div class="col-md-3 p-5 border border-info">
      @if(count($supplierDetails) > 1)
          <?php $x = 1; ?>
          <form method="post" action="bulk-transfer">
              @csrf
              @foreach($supplierDetails as $d)
                  <div class="form-group">
                      <small>{{$d['name']}}</small>
                      <input type="hidden" value="{{$d['code']}}" name="code{{$x}}" />
                      <input type="text" class="form-control" name="amount{{$x}}" placeholder="enter amount e.g 25000" required/>
                  </div>
                  <?php $x++; ?>
              @endforeach
              <input type="hidden" value="{{count($supplierDetails)}}" name="count-total" />
              <button type="submit" class="btn btn-info text-white w-100"> make transfer </button>
          </form>
      @endif
    </div>

  </div>
  <div class="row justify-content-center">
    <img class="my-3" src="img/paystack_logo_1.png" width="150px" height="25px"/>
  </div>
</div>





@endsection
