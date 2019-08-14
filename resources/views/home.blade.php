@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 justify-content-center">
            @include('inc.alerts.msg')

            <div class="container">
                  <div class="row">
                    <div class="col-md-12 bg-info border border-info">
                      <div class="row">
                        <div class="col-md-12 py-3">
                          <h3 class="float-left m-1">  </h3>

                            <a href="#" class="btn btn-light float-right m-1" data-toggle="modal" data-target="#add-new-supplier">
                             Add Supplier <i class="fa fa-plus fa-fw"></i>
                             </a>

                            <a class="btn btn-danger text-white float-right m-1 @if(count($supplierLists) < 2) {{'disabled'}} @endif" data-toggle="modal" data-target="#bulk-transfer">
                              Bulk transfer
                            </a>

                        </div>
                      </div>
                      <div class="row justify-content-center">

                        <div class="col-md-4 bg-info pb-3">
                          @if($walletBalance != null)
                          <div class="text-center bg-white p-1 mb-4">
                            WALLET BALANCE<br/>
                            <span class="text-danger">{{$walletBalance}}</span>
                          </div>
                          @endif
                          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                              Suppliers<span class="badge badge-light float-right mt-1">{{count($supplierLists)}}</span>
                            </a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                              Transfers<span class="badge badge-light float-right mt-1">{{count($transferLists)}}</span>
                            </a>
                          </div>
                        </div>
                        <div class="col-md-8 bg-light py-3">
                          <div class="tab-content" id="v-pills-tabContent">
                              <!-- supplier tab -->
                               <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                  <h3 class="my-2"> SUPPLIERS </h3>
                                  <hr class="mt-2 mb-4">

                                  @if(count($supplierLists) > 0)
                                  @foreach($supplierLists as $supplier)
                                      <div class="row mb-3">
                                          <div class="col-md-8">{{$supplier['name']}}</div>
                                          <div class="col-md-4">
                                            <a class="btn btn-success text-white" data-toggle="modal" data-target="#transfer-{{$supplier['id']}}" title="transfer funds"><i class="fa fa-send"></i></a>
                                            <a class="btn btn-secondary text-white" data-toggle="modal" data-target="#edit-{{$supplier['id']}}" title="edit supplier"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-danger text-white" data-toggle="modal" data-target="#delete-{{$supplier['id']}}" title="delete supplier"><i class="fa fa-trash"></i></a>
                                          </div>
                                      </div>

                                      <!-- transfer modal -->
                                      @include('inc.modals.suppliers.transfer-to-supplier-modal')
                                      <!-- transfer modal -->

                                      <!-- edit supplier modal -->
                                      @include('inc.modals.suppliers.edit-supplier-modal')
                                      <!-- edit supplier modal -->

                                    <!-- delete supplier modal-->
                                    @include('inc.modals.suppliers.delete-supplier-modal')
                                    <!-- delete supplier modal -->
                                  @endforeach
                                  @else
                                    <h5>
                                       No supplier has been added...
                                      <a class="btn btn-danger text-light" data-toggle="modal" data-target="#add-new-supplier"> Add Supplier </a>
                                    </h5>

                                  @endif
                               </div><!-- supplier tab -->

                               <!-- transfer tab -->
                               <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                 <h3 class="mb-2 mt-2"> TRANSFERS </h3>
                                 <hr class="mt-2 mb-4">


                                 @if(count($transferLists) > 0)

                                    <nav>
                                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-home" aria-selected="true">All</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-success" role="tab" aria-controls="nav-profile" aria-selected="false">Success</a>
                                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-pending" role="tab" aria-controls="nav-contact" aria-selected="false">Pending</a>
                                          <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-abandoned" role="tab" aria-controls="nav-contact" aria-selected="false">Abandoned</a>
                                      </div>
                                    </nav>
                                    <div class="tab-content my-4" id="nav-tabContent">
                                      <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-home-tab">
                                        @foreach($transferLists as $transfer)
                                          <div class="row mb-3">
                                              <div class="col-md-9">
                                                  <a id="tranx">
                                                    {{$transfer['name']}}
                                                    @if($transfer['status'] == 'success')
                                                       <span class="badge badge-success text-white p-2 lead">N{{$transfer['amount']}}</span>
                                                    @elseif($transfer['status'] == 'abandoned')
                                                         <span class="badge badge-danger text-white p-2 lead">N{{$transfer['amount']}}</span>
                                                    @else
                                                         <span class="badge badge-primary text-white p-2 lead">N{{$transfer['amount']}}</span>
                                                    @endif
                                                  </a>
                                              </div>
                                              <div class="col-md-3">
                                                <a class="btn btn-danger text-light" data-toggle="modal" data-target="#all-{{$transfer['id']}}" title="view transfer details">
                                                   <i class="fa fa-eye"></i>
                                                  </a>
                                              </div>
                                          </div>
                                          @include('inc.modals.transfers.all-transfer-modal')
                                        @endforeach
                                      </div>
                                      <div class="tab-pane fade" id="nav-success" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        @foreach($transferLists as $transfer)
                                          @if($transfer['status'] == 'success')
                                            <div class="row mb-3">
                                                <div class="col-md-9">
                                                    <a id="tranx">
                                                      {{$transfer['name']}}
                                                      @if($transfer['status'] == 'success')
                                                         <span class="badge badge-success text-white p-2 lead">N{{$transfer['amount']}}</span>
                                                      @endif
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                  <a class="btn btn-danger text-light" data-toggle="modal" data-target="#success-{{$transfer['id']}}" title="view transfer details">
                                                     <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @include('inc.modals.transfers.success-transfer-modal')
                                            @endif

                                        @endforeach
                                      </div>
                                      <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-contact-tab">
                                        @foreach($transferLists as $transfer)
                                          @if($transfer['status'] == 'otp')
                                            <div class="row mb-3">
                                                <div class="col-md-9">
                                                    <a id="tranx">
                                                      {{$transfer['name']}}

                                                         <span class="badge badge-primary text-white p-2 lead">N{{$transfer['amount']}}</span>

                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                  <a class="btn btn-danger text-light" data-toggle="modal" data-target="#pending-{{$transfer['id']}}" title="view transfer details">
                                                     <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @include('inc.modals.transfers.pending-transfer-modal')
                                            @endif
                                        @endforeach
                                      </div>
                                      <div class="tab-pane fade" id="nav-abandoned" role="tabpanel" aria-labelledby="nav-contact-tab">
                                        @foreach($transferLists as $transfer)
                                          @if($transfer['status'] == 'abandoned')
                                            <div class="row mb-3">
                                                <div class="col-md-9">
                                                    <a id="tranx">
                                                      {{$transfer['name']}}

                                                         <span class="badge badge-danger text-white p-2 lead">N{{$transfer['amount']}}</span>

                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                  <a class="btn btn-danger text-light" data-toggle="modal" data-target="#abandoned-{{$transfer['id']}}" title="view transfer details">
                                                     <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @include('inc.modals.transfers.abandoned-transfer-modal')
                                            @endif
                                        @endforeach
                                      </div>

                                    </div>

                                 @else
                                    <h5> You haven't made any transfers... </h5>
                                 @endif



                               </div>
                               <!-- transfer tab -->
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
            </div>


            <!-- add new supplier modal -->
            @include('inc.modals.suppliers.new-supplier-modal')

            <!-- add new supplier modal -->

            <!-- bulk transfer modal -->
            @include('inc.modals.transfers.bulk-transfer-modal')

            <!-- bulk transfer modal -->

          <img class="my-3" style="margin-left: 40%" src="img/paystack_logo_1.png" width="150px" height="30px"/>

        </div>
    </div>
</div>

@endsection
