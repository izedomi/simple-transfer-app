<div class="modal fade" id="abandoned-{{$transfer['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center justify-content-center">
        <h6 class="modal-title text-center" id="exampleModalLabel">about your transfer</h6>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row text-center justify-content-center mb-3">
            <div class="col-md-12 text-start text-primary">
               N{{$transfer['amount']}} to {{$transfer['name']}}
            </div>
            <div class="col-md-12 text-end">
              {{$transfer['account_no']}} |  {{$transfer['bank_name']}}
            </div>
            <hr>
          </div>
           <div class="row">
             <div class="col-md-5 text-start">
               <p> Status </p>
               <p> Created </p>
               <p> Transfer Code </p>
               <p> Transfer Reference </p>
               <p> Transfer From </p>
             </div>
             <div class="col-md-7 justify-content-end">
               <p>

                 {{$transfer['status']}}

                     <i class="fa fa-times fa-fw text-danger"></i>

               </p>
               <p> {{$transfer['date']}} | {{$transfer['time']}} </p>
               <p> {{$transfer['transfer_code']}} </p>
               <p> {{$transfer['reference']}} </p>
               <p> NGN Balance </p>
             </div>
             <hr>
             <div class="col-md-12">
               Notes: {{$transfer['reason']}}
             </div>

           </div>
        </div>
      </div>
    </div>
  </div>
</div>
