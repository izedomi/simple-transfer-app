
                                      <div class="modal fade" id="edit-{{$supplier['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                              <form method="post" action="update-supplier">
                                                @csrf
                                                <div class="form-group">
                                                  <label for="recipient-name" class="col-form-label">Supplier Name</label>
                                                    <input type="text" class="form-control" name="supplier-name" value="{{$supplier['name']}}">
                                                </div>
                                                <div class="form-group">
                                                  <label for="account-no" class="col-form-label">Account Number</label>
                                                  <input type="text" class="form-control" name="account-no" value="{{$supplier['details']['account_number']}}"id="account-no">
                                                </div>
                                                <div class="form-group">
                                                  <label for="message-text" class="col-form-label">Select Bank</label>
                                                    <select name="bank-details" class="custom-select">
                                                      <option value="{{$supplier['details']['bank_name']}}|{{$supplier['details']['bank_code']}}">
                                                          {{$supplier['details']['bank_name']}}
                                                      </option>
                                                      @foreach($bankLists as $bank)
                                                         <option value="{{$bank['name']}}|{{$bank['code']}}">
                                                             {{$bank['name']}}
                                                         </option>
                                                     @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                  <hr class="my-3">
                                                  <input type="hidden" name="recipient-code" value="{{$supplier['recipient_code']}}" />
                                                  <button type="submit" value="update" name="update-supplier" class="btn btn-primary">Update Details </button>
                                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
