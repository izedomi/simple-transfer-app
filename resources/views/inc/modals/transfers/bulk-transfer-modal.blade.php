<div class="modal fade" id="bulk-transfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bulk Transfer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="bulk-transfer-recipients">
          @csrf

          <div class="form-group">
            <label for="message-text" class="col-form-label">Select Suppliers</label>
              <select name="suppliers[]" class="custom-select" multiple>
                @foreach($supplierLists as $supplier)
                   <option value="{{$supplier['name']}}|{{$supplier['recipient_code']}}">
                       {{$supplier['name']}}
                   </option>
                @endforeach
              </select>
          </div>
          <div class="form-group">
            <hr class="my-3">
            <button type="submit" name="submit" class="btn btn-primary">Continue</button>
              <img  class="float-right mt-2" src="https://farmkart.ng/wp-content/plugins/woo-paystack/assets/images/paystackwhite.png" width="150px" height="35px" />
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
