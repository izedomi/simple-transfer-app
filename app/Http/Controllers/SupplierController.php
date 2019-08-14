<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\App\Supplier;

use Illuminate\Http\Request;
use App\Supplier;
use App\Http\Controllers\Utility;


class SupplierController extends Controller{
    //

    public function __construct(){$this->middleware('auth');}
    public function index(){

        $sLists = $this->get_list_of_suppliers();
        $tLists = $this->get_list_of_transfers();
        $bLists = $this->get_list_of_banks();
        $walletBalanceInKobo = $this->get_wallet_balance();

        $amountInNaira = Utility::amount_delimeter($walletBalanceInKobo / 100);
        $walletBalance = "NGN".$amountInNaira;
        //return $sLists;
        //return $tLists->paginate(15)
        if($sLists == null){$sLists = array();}
        $newTLists = array();
        if($tLists != null){
           foreach ($tLists as $value) {

               $amount = $value['amount']/100;
               $amount = Utility::amount_delimeter($amount);
               $timestamp = explode("T", $value['createdAt']);
               $date = $timestamp[0];
               $timeZone = $timestamp[1];
               $time = explode(".", $timeZone);
               $arg =  array(
                  'id' => $value['id'],
                  'amount' => $amount,
                  'date' => $date,
                  'time' => $time[0],
                  'transfer_code' => $value['transfer_code'],
                  'name' => $value['recipient']['name'],
                  'reason' => $value['reason'],
                  'status' => $value['status'],
                  'reference' => $value['reference'],
                  'account_no' => $value['recipient']['details']['account_number'],
                  'bank_name' => $value['recipient']['details']['bank_name']
               );
               array_push($newTLists, $arg);
          }
        }

        //return $b;

        $data = array(
           'supplierLists' => $sLists,
           'bankLists' => $bLists,
           'transferLists' => $newTLists,
           'walletBalance' => $walletBalance

        );

        return view('home')->with($data);
    }
    public function add_supplier(Request $request){
      //return $request->all();

        $this->validate($request, [
            'supplier-name' => 'required',
            'account-no' => 'required',
        ]);

        $supplierName = $request->input('supplier-name');
        $accountNo = $request->input('account-no');
        $bankDetails = explode("|", $request->input('bank'));
        $bankName = $bankDetails[0];
        $bankCode = $bankDetails[1];

        //return $bankCode;
        //return $bankCode;

        //resolve account Number
        $tranx = $this->resolve_account_number($accountNo, $bankCode);
        if(!$tranx['status']){
           return redirect('/home')->with('error', "Account verification failed. ". $tranx['message']);
        }
        if($tranx['status']){
             if(count($tranx['data']) > 0){
                 $accountName = $tranx['data']['account_name'];

                 //create transfer reciepient
                 $response = $this->create_transfer_recipient($supplierName, $accountName, $accountNo, $bankCode);

                 if(!$response['status']){
                   return redirect('/home')->with('error', $response['message']);
                  }

                 if($response['status']){
                     if($response['data']['recipient_code'] != null){
                         $supplierId = $response['data']['recipient_code'];


                        $s = Supplier::where('supplier_id', $supplierId)->where('bank_code', $bankCode)->where('account_no', $accountNo)->get();

                        //if supplier dosen't exist
                        if(count($s) < 1){
                          $newSupplier = new Supplier();

                          $newSupplier->supplier_id = $supplierId;
                          $newSupplier->supplier_name = $supplierName;
                          $newSupplier->bank_name = $bankName;
                          $newSupplier->bank_code = $bankCode;
                          $newSupplier->account_name = $accountName;
                          $newSupplier->account_no = $accountNo;
                          $newSupplier->save();
                        }

                        // if supplier already exists, update just the supplier name
                        if(count($s) == 1){
                          $newSupplier = new Supplier();
                          $newSupplier->supplier_name = $supplierName;
                          $newSupplier->save();
                        }

                        return redirect('/home')->with('success', 'New Supplier Created');
                     }
                     else{
                       return redirect('/home')->with('error', 'Operation Failed: Could Not add new Supplier');
                     }
                 }
             }
         }
        //return $accountName;

    }
    public function update_supplier(Request $request){
         //return $request->all();
         $supplierName = $request->input('supplier-name');
         $accountNo = $request->input('account-no');
         $recipientCode = $request->input('recipient-code');
         $bankDetails = explode("|", $request->input('bank-details'));
         $bankName = $bankDetails[0];
         $bankCode = $bankDetails[1];

         // no changes was made
         $a = Supplier::where('supplier_id', $recipientCode)
         ->where('bank_code', $bankCode)
         ->where('account_no', $accountNo)
         ->where('supplier_name', $supplierName)
         ->get();

         //return $a;
         if(count($a) > 0){
            return redirect("/home")->with('error', "No changes was made!");
         }

         //only the supplier name was changed
         $s = Supplier::where('supplier_id', $recipientCode)->where('bank_code', $bankCode)->where('account_no', $accountNo)->get();
         //return $s;
         if(count($s) > 0){

              //api update call
             $response = $this->update_transfer_recipient($supplierName, $recipientCode);

             //return $response;

             if(!$response['status']){
                return redirect('/home')->with('error', $response['message'] . ". Failed to update supplier details");
              }

             if($response['status']){

                //api update supplier was successful, update database records
                 if($tranx['message'] == "Recipient updated"){
                   $s[0]['supplier_id'] = $supplierName;
                   $s[0]->save();
                   return redirect("/home")->with('success', "Supplier updated successfully");
                 }
                 else{
                     return redirect('/home')->with('error', 'Operation Failed: Could Not updated supplier details');
                 }

             }

         }
         else{  // account details was changed

               //resolve account Number
               $tranx = $this->resolve_account_number($accountNo, $bankCode);
               if(!$tranx['status']){
                  return redirect('/home')->with('error', "Account verification failed. ". $tranx['message']);
               }
               if($tranx['status']){
                    if(count($tranx['data']) > 0){
                        $accountName = $tranx['data']['account_name'];

                        //create transfer reciepient
                        $response = $this->create_transfer_recipient($supplierName, $accountName, $accountNo, $bankCode);
                        //return $supplierId;

                        if(!$response['status']){
                          return redirect('/home')->with('error', $response['message']);
                         }

                        if($response['status']){
                            if($response['data']['recipient_code'] != null){
                                $supplierId = $response['data']['recipient_code'];

                                //save to database
                                $newSupplier = new Supplier();

                                $newSupplier->supplier_id = $supplierId;
                                $newSupplier->supplier_name = $supplierName;
                                $newSupplier->bank_name = $bankName;
                                $newSupplier->bank_code = $bankCode;
                                $newSupplier->account_name = $accountName;
                                $newSupplier->account_no = $accountNo;
                                $newSupplier->save();


                                if($this->delete_transfer_recipient($recipientCode)){
                                  $s = Supplier::where('supplier_id', $recipientCode)->get();
                                  $s[0]->delete();
                                }

                                return redirect('/home')->with('success', 'Supplier updated successfully');
                            }
                            else{
                              return redirect('/home')->with('error', 'Operation Failed: Could Not add new Supplier');
                            }
                        }
                    }
                }




           return redirect('/home')->with('error', 'Failed to update supplier account details');

         }

    }
    public function delete_supplier(Request $request){
          //return $request->all();
          $recipient_code = $request->input('recipient_code');

          if($this->delete_transfer_recipient($recipient_code)){
             $supplier = Supplier::where('supplier_id', $recipient_code)->get();
             if(count($supplier) > 0){$supplier[0]->delete();}
             return redirect('/home')->with('success', 'Supplier deleted successfully');
          }
          else {
              return redirect('/home')->with('error', "Failed to delete supplier");
          }
    }
    public function transfer_cash(Request $request){
      $this->validate($request, [
          'payment-desc' => 'required',
          'amount' => 'required',
      ]);

      $amount = $request->input('amount');
      $recipient_code = $request->input('supplier-id');
      $reason = $request->input('payment-desc');
      $amountInKobo = $amount * 100;

      //return $request->all();

      $tranx = $this->initiate_transfer($amountInKobo, $recipient_code, $reason);

      if($tranx['status']){
          if($tranx['data'] != null){

              //transfer failed
              if($tranx['data']['status'] == 'failed'){
                return redirect('/home')->with('error', "Transfer Failed");
              }
              //otp is enabled
              elseif($tranx['data']['status'] == 'otp'){
                   return view('verify-otp-transfer')->with("transfer_code", $tranx['data']['transfer_code']);
              }
              else{
                 return redirect('/home')->with('success', "Transfer Successful");
              }
          }
          else{
              return view('/home')->with('error', 'Operation Failed: Please try again');
          }
      }
      else{
          return redirect('/home')->with('error', $tranx['message']);
      }

      //return $tranx;


    }
    public function finalize_transfer(Request $request){
      $this->validate($request, [
          'otp' => 'required'
      ]);

      $otp = $request->input('otp');
      $transfer_code = $request->input('transfer-code');

      $tranx = $this->finalize_otp_transfer($otp, $transfer_code);
      //return $request->all();
      //return $s;
      if(!$tranx['status']){
         return redirect('/home')->with('error', $tranx['message']);
      }
      return redirect('/home')->with('success', "Transfer Successful");

    }
    public function get_bulk_transfer_recipients(Request $request){
          //return $request->all();
          $supplierDetails = array();
          foreach($request->input('suppliers') as $supplier){
            $ss = explode("|", $supplier);
            $s = array(
              'name' => $ss[0],
              'code' => $ss[1]
            );

            array_push($supplierDetails, $s);
          }

          //ensure more than 1 supplier is selected for bulk transfer
          if(count($supplierDetails) < 2){
             return redirect('/home')->with('error', 'More than 1 supplier must be selected for bulk transfer');
          }

          //return $supplierDetails;
          return view('bulk-transfer')->with('supplierDetails', $supplierDetails);
    }
    public function bulk_transfer(Request $request){

          $paymentArray = array();
          $supplierCount = $request->input('count-total');
          $x = 1;

          while ($x <= $supplierCount){
            $a = array(
              'recipient' => "{$request->input("code{$x}")}",
              'amount' => $request->input("amount{$x}") * 100
            );

            array_push($paymentArray, $a);
            $x++;
          }

          //return $paymentArray;


          $c = array(
            'currency' => 'NGN',
            'source' => 'balance',
            'transfers' => $paymentArray
          );

      /*
        $c = array(
          'currency' => 'NGN',
          'source' => 'balance',
          'transfers' => [
             array(
               'recipient' => 'RCP_mbl9kml7k2u2y0q',
               'amount' => 50000
             ),
             array(
               'recipient' => 'RCP_y98zv9c5229233d',
               'amount' => 70000
             )
            ]
        );

        */

        $response = $this->initiate_bulk_transfer($c);
        if($response == '00'){
          return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
        }
        if($response['status']){
          return redirect('/home')->with('success', 'Bulk Transfer operation successful. '.$response['message']);
        }
        return redirect('/home')->with('error', "Bulk Transfer operation couldn't be completed. Please try again later");

    }
    public function resend_otp(){
      //return $transferCode;
      if(isset($_GET['tcode'])){
        $transferCode = $_GET['tcode'];


        $response = $this->resend_transfer_otp($transferCode);
        //return $response;
        $msg = "";
        if($response == '00'){
          $msg = "Ooops.Error occured...please try again";
        }
        if($response['status']){
          $msg = $response['message'];
        }
        if(!$response['status']){
           $msg = "Couldn't not resend otp...please try again";
        }

        $data = array(
          'transfer_code' => $transferCode,
          'msg' => $msg,
        );

        //return view('verify-otp-transfer')->with("transfer_code", $transferCode
        return view('verify-otp-transfer')->with($data);


      }

    }



    //API CALLS
    private function get_list_of_banks(){
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
             // "authorization: $this->testSecretKey",
              "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f"

            ],
          ));

          $response = curl_exec($curl);
          $err = curl_error($curl);

          if($err){
            // there was an error contacting the Paystack API
              return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
          }

          $tranx = json_decode($response, true);

          //return $tranx;

          //return $tranx['data'];

          if(!$tranx['status']){ die('API returned error: '.$tranx['message']);}

          if($tranx['status']){
              if(count($tranx['data']) > 0){
                  return $tranx['data'];
              }
          }
    }
    private function get_wallet_balance(){
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/balance",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
         // "authorization: $this->testSecretKey",
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the Paystack API
        return "";
      }

      $tranx = json_decode($response, true);

      //return $tranx;

      //return $tranx['data'];

      if(!$tranx['status']){
        return "";
      }

      if($tranx['status']){
          if(($tranx['data']) != null){
              return $tranx['data'][0]['balance'];
          }
          else{
            return "";
          }
      }

    }
    private function resolve_account_number($accountNo, $bankCode){
      $url = "https://api.paystack.co/bank/resolve?account_number={$accountNo}&bank_code={$bankCode}";
      //return $url;
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
         // "authorization: $this->testSecretKey",
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f"

        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
          return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
      }

      $tranx = json_decode($response, true);

      return $tranx;


    }
    private function create_transfer_recipient($supplierName, $accountName, $accountNo, $bankCode){
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transferrecipient",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
            'type'=>'nuban',
            'name'=> "{$supplierName}",
            'account_number'=> "{$accountNo}",
            'bank_code'=> "{$bankCode}",
            'currency'=>'NGN',
            'metadata'=>array('accountName' => $accountName,)
            ]),
            CURLOPT_HTTPHEADER => [
              "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
              "content-type: application/json"
            ],
          ));

          $response = curl_exec($curl);
          $err = curl_error($curl);

          if($err){
            // there was an error contacting the Paystack API
            return 0;
            return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
            //die('Curl returned error: ' . $err);
          }

          $tranx = json_decode($response, true);

          return $tranx;

      }
    private function get_list_of_suppliers(){

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transferrecipient",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
         // "authorization: $this->testSecretKey",
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the Paystack API
        return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
      }

      $tranx = json_decode($response, true);

      //return $tranx;

      //return $tranx['data'];

      if(!$tranx['status']){ die('API returned error: '.$tranx['message']);}

      if($tranx['status']){
          if(count($tranx['data']) > 0){
              return $tranx['data'];
          }
      }

    }
    private function initiate_transfer($amount, $recipient_code, $reason){
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transfer",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
        'source'=>'balance',
        'recipient'=> "{$recipient_code}",
        'amount'=> $amount,
        'reason'=> "{$reason}",
        'currency'=>'NGN'
        ]),
        CURLOPT_HTTPHEADER => [
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
          "content-type: application/json"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the Paystack API
          return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
        //die('Curl returned error: ' . $err);
      }

      $tranx = json_decode($response, true);

      return $tranx;


    }
    private function get_list_of_transfers(){
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transfer",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
         // "authorization: $this->testSecretKey",
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the Paystack API
          return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
      }

      $tranx = json_decode($response, true);

      //return $tranx;

      //return $tranx['data'];

      if(!$tranx['status']){
        return redirect('/home')->with('error', $tranx['message']);
      }

      if($tranx['status']){
          return $tranx['data'];
      }
    }
    private function delete_transfer_recipient($recipient_code){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transferrecipient/".$recipient_code,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "DELETE",
          CURLOPT_HTTPHEADER => [
            "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
            "content-type: application/json"
          ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          // there was an error contacting the Paystack API
          return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
          //die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        //return $tranx;

        //return $tranx['data'];

        return $tranx['status'];

          /*
          if(!$tranx['status']){
            //die('API returned error: '.$tranx['message']);
            return $tranx['status'];
          }

          if($tranx['status']){
              return $tranx['status'];
          }
        */

    }
    private function update_transfer_recipient($supplierName, $recipientCode){
      $curl = curl_init();
      $url = "https://api.paystack.co/transferrecipient/{$recipientCode}";
      //return $url;
      $d= array("name" => "{$supplierName}");
      $data = json_encode($d);
      //after json_encode, $data = {"name":"VesGroups"}
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
          "content-type: application/json",
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      //return $response;

      if($err){
        // there was an error contacting the Paystack API
        return redirect("/home")->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
        //die('Curl returned error: ' . $err);
      }

      $tranx = json_decode($response, true);

      return $tranx;

    }
    private function finalize_otp_transfer($otp, $transfer_code){
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transfer/finalize_transfer",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
        'otp'=>"{$otp}",
        'transfer_code'=> "{$transfer_code}",
        ]),
        CURLOPT_HTTPHEADER => [
          "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
          "content-type: application/json"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the Paystack API
        return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
        //die('Curl returned error: ' . $err);
      }

      $tranx = json_decode($response, true);

      return $tranx;

    }
    private function resend_transfer_otp($transferCode){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transfer/resend_otp",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
          'reason'=>'transfer',
          'transfer_code'=> "{$transferCode}",
          ]),
          CURLOPT_HTTPHEADER => [
            "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
            "content-type: application/json"
          ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          // there was an error contacting the Paystack API
          return "00";
        //  return redirect('/home')->with('error', "Ooops, coudn't connect to the server\nPlease refresh the page and try again");
          //die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        return $tranx;
    }
    private function initiate_bulk_transfer($paymentArray){

        //  return json_encode($paymentArray);

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transfer",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($paymentArray),
          CURLOPT_HTTPHEADER => [
            "authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
            "content-type: application/json"
          ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          // there was an error contacting the Paystack API
          return '00';
          //die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        return $tranx;

    }
}
