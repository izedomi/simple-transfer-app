<div class="modal fade" id="transfer-{{$supplier['id']}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Transfer Cash To {{$supplier['name']}} </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <small> Bank Name: {{$supplier['details']['bank_name']}} </small><br/>
        <small> Account Name: {{$supplier['metadata']['accountName']}} </small><br/>
        <small> Account Number: {{$supplier['details']['account_number']}} </small><br/>
        <form method="post" action="transfer-cash">
            @csrf
            <div class="form-group">
              <label for="amount" class="col-form-label text-danger">Enter Amount To Transfer</label>
              <input type="hidden" name="supplier-id" value="{{$supplier['recipient_code']}}" />
              <input type="text" class="form-control" name="amount" id="amount" placeholder="e.g 25000">
              <label for='payment-desc' class="col-form-label"> Transfer Purpose </label><br/>
              <textarea name="payment-desc" id='payment-desc' class="form-control"></textarea>
            </div>
            <div class="form-group">
              <hr class="my-3">
              <button type="submit" name="transfer-cash" class="btn btn-primary">Transfer</button>
              <img  class="float-right mt-2" src="https://farmkart.ng/wp-content/plugins/woo-paystack/assets/images/paystackwhite.png" width="150px" height="35px" />
              <hr class="my-3">


            </div>
        </form>
      </div>

    </div>
  </div>
</div>
