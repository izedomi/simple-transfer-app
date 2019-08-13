<div class="modal fade" id="delete-{{$supplier['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete {{$supplier['name']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-danger"> Are you sure ? </p>
      </div>
      <div class="modal-footer">
        <form method="post" action="delete-supplier">
          @csrf
          <div class="form-group">
            <hr class="my-3">
            <input type="hidden" name="recipient_code" value="{{$supplier['recipient_code']}}" />
            <button type="submit" value="delete" name="delete-supplier" class="btn btn-primary">Delete</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
