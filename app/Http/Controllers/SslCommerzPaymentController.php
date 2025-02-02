<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        // return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        // return view('exampleHosted');
    }

    public function index(Request $request)
    {

        // dd($request->all());
        if(auth()->user()->phone == null){
            return redirect()->back()->with('error', 'Please update your phone number first.');
        }

        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = auth()->user()->name;
        $post_data['cus_email'] = auth()->user()->email;
        $post_data['cus_add1'] = 'Dhaka, Bangladesh';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = auth()->user()->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Subscription";
        $post_data['product_category'] = "Digital";
        $post_data['product_profile'] = "digital-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        // dd($post_data);

        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('subscriptions')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'user_id' => auth()->user()->id,
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => env('SUBSCRIPTION_PRICE'),
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => env('CURRENCY')
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function payViaAjax(Request $request)
    {

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('subscriptions')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'user_id' => auth()->user()->id,
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function success(Request $request)
    {
        echo "Transaction is Successful";

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $subscription = DB::table('subscriptions')
            ->where('transaction_id', $tran_id)->first();

        if ($subscription->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_subscription = DB::table('subscriptions')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Complete']);

                $user = DB::table('users')->where('id', $subscription->user_id)->first();
                $user->is_premium = 1;
                $user->save();

                // echo "<br >Transaction is successfully Completed";
                session()->flash('success', 'Transaction is successfully Completed');
                return redirect()->route('dashboard');
            }
        } else if ($subscription->status == 'Processing' || $subscription->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            // echo "Transaction is successfully Completed";
            session()->flash('success', 'Transaction is successfully Completed');
            return redirect()->route('dashboard');
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            // echo "Invalid Transaction";
            session()->flash('error', 'Invalid Transaction');
            return redirect()->route('dashboard');
        }


    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('subscriptions')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_subscription = DB::table('subscriptions')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            // echo "Transaction is Falied";
            session()->flash('error', 'Transaction is Falied');
            return redirect()->route('dashboard');
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            // echo "Transaction is already Successful";
            session()->flash('success', 'Transaction is already Successful');
            return redirect()->route('dashboard');
        } else {
            // echo "Transaction is Invalid";
            session()->flash('error', 'Transaction is Invalid');
            return redirect()->route('dashboard');
        }

    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $subscription = DB::table('subscriptions')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($subscription->status == 'Pending') {
            $update_subscription = DB::table('subscriptions')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            // echo "Transaction is Cancel";
            session()->flash('error', 'Transaction is Cancel');
            return redirect()->route('dashboard');
        } else if ($subscription->status == 'Processing' || $subscription->status == 'Complete') {
            // echo "Transaction is already Successful";
            session()->flash('success', 'Transaction is already Successful');
            return redirect()->route('dashboard');
        } else {
            // echo "Transaction is Invalid";
            session()->flash('error', 'Transaction is Invalid');
            return redirect()->route('dashboard');
        }


    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $subscription = DB::table('subscriptions')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($subscription->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $subscription->amount, $subscription->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_subscription = DB::table('subscriptions')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Complete']);

                    $user = DB::table('users')->where('id', $subscription->user_id)->first();
                    $user->is_premium = 1;
                    $user->save();

                    return redirect()->route('dashboard')->with('success', 'Transaction is successfully Completed');
                }
            } else if ($subscription->status == 'Processing' || $subscription->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                session()->flash('success', 'Transaction is already successfully Completed');
                return redirect()->route('dashboard');
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                session()->flash('error', 'Invalid Transaction');
                return redirect()->route('dashboard');
            }
        } else {
            // echo "Invalid Data";
            session()->flash('error', 'Invalid Data');
            return redirect()->route('dashboard');
        }
    }

}
