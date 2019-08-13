<div class="modal fade" id="add-new-supplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="add-supplier">
          @csrf
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Supplier Name</label>
            <input type="text" class="form-control" name="supplier-name" required>
          </div>
          <div class="form-group">
            <label for="account-no" class="col-form-label">Account Number</label>
            <input type="text" class="form-control" name="account-no" minlength="10" maxlength="10" required>
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Select Bank</label>
              <select name="bank" class="custom-select">
                @foreach($bankLists as $bank)
                   <option value="{{$bank['name']}}|{{$bank['code']}}">
                       {{$bank['name']}}
                   </option>
               @endforeach
              </select>
          </div>
          <div class="form-group">
            <hr class="my-3">
            <button type="submit" name="submit" class="btn btn-primary">Add Supplier</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
