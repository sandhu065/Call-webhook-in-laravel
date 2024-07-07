<?php 
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\Webhook\Signature;
use Illuminate\Http\Response;
use DB;

 // Steps 
//  1) composer require stripe/stripe-php
//  2)  Keep the keys in .env files or make stripe.php in config and kept there 
//  STRIPE_KEY=<your-stripe-key>
//  STRIPE_SECRET=<your-stripe-secret>
//  3) Set the url in webhook in strpe 
//  4) To check whether webhook working or not 
//     hit that url in postman with json data kept in raw
//     This is for charge succeed
//     {
//         "id": "evt_3PZa7RSBJGtnuEwk0tSVupEl",
//         "object": "event",
//         "api_version": "2023-10-16",
//         "created": 1720278113,
//         "data": {
//           "object": {
//             "id": "ch_3PZa7RSBJGtnuEwk0FwkKm8n",
//             "object": "charge",
//             "amount": 600,
//             "amount_captured": 600,
//             "amount_refunded": 0,
//             "application": null,
//             "application_fee": null,
//             "application_fee_amount": null,
//             "balance_transaction": "txn_3PZa7RSBJGtnuEwk0MfizV6l",
//             "billing_details": {
//               "address": {
//                 "city": null,
//                 "country": "IN",
//                 "line1": null,
//                 "line2": null,
//                 "postal_code": null,
//                 "state": null
//               },
//               "email": "sandhu065@gmail.com",
//               "name": "singh",
//               "phone": null
//             },
//             "calculated_statement_descriptor": "ABCDEF",
//             "captured": true,
//             "created": 1720278112,
//             "currency": "inr",
//             "customer": null,
//             "description": null,
//             "destination": null,
//             "dispute": null,
//             "disputed": false,
//             "failure_balance_transaction": null,
//             "failure_code": null,
//             "failure_message": null,
//             "fraud_details": {},
//             "invoice": null,
//             "livemode": false,
//             "metadata": {},
//             "on_behalf_of": null,
//             "order": null,
//             "outcome": {
//               "network_status": "approved_by_network",
//               "reason": null,
//               "risk_level": "normal",
//               "risk_score": 48,
//               "seller_message": "Payment complete.",
//               "type": "authorized"
//             },
//             "paid": true,
//             "payment_intent": "pi_3PZa7RSBJGtnuEwk0hWBx1Kq",
//             "payment_method": "pm_1PZa7QSBJGtnuEwkXf6YhZda",
//             "payment_method_details": {
//               "card": {
//                 "amount_authorized": 600,
//                 "brand": "visa",
//                 "checks": {
//                   "address_line1_check": null,
//                   "address_postal_code_check": null,
//                   "cvc_check": "pass"
//                 },
//                 "country": "IN",
//                 "exp_month": 10,
//                 "exp_year": 2028,
//                 "extended_authorization": {
//                   "status": "disabled"
//                 },
//                 "fingerprint": "rhMrr6Nx8EM5gUBE",
//                 "funding": "credit",
//                 "incremental_authorization": {
//                   "status": "unavailable"
//                 },
//                 "installments": null,
//                 "last4": "0008",
//                 "mandate": null,
//                 "multicapture": {
//                   "status": "unavailable"
//                 },
//                 "network": "visa",
//                 "network_token": null,
//                 "overcapture": {
//                   "maximum_amount_capturable": 600,
//                   "status": "unavailable"
//                 },
//                 "three_d_secure": {
//                   "authentication_flow": "challenge",
//                   "electronic_commerce_indicator": "05",
//                   "exemption_indicator": null,
//                   "result": "authenticated",
//                   "result_reason": null,
//                   "transaction_id": "290bb325-e197-4de8-ae74-968a3edb35f1",
//                   "version": "2.1.0"
//                 },
//                 "wallet": null
//               },
//               "type": "card"
//             },
//             "radar_options": {},
//             "receipt_email": null,
//             "receipt_number": null,
//             "receipt_url": "https://pay.stripe.com/receipts/payment/CAcaFwoVYWNjdF8xT1I3bG5TQkpHdG51RXdrKOG4pbQGMgZUwyQX3yk6LBayJZQF_CoP9gn8OzV9f8n2k-AqTFVGa1jPJ_3bLQnG7ZEZsni8w-CpW93h",
//             "refunded": false,
//             "review": null,
//             "shipping": null,
//             "source": null,
//             "source_transfer": null,
//             "statement_descriptor": null,
//             "statement_descriptor_suffix": null,
//             "status": "succeeded",
//             "transfer_data": null,
//             "transfer_group": null
//           }
//         },
//         "livemode": false,
//         "pending_webhooks": 1,
//         "request": {
//           "id": null,
//           "idempotency_key": "pi_3PZa7RSBJGtnuEwk0hWBx1Kq-payatt_3PZa7RSBJGtnuEwk0WQXaEAN"
//         },
//         "type": "charge.succeeded"
//       }
class StripeController extends Controller
{
    public function checkout()
    {
        return view('checkout');
    }
 
    public function session(Request $request)
    {
    
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        $productname = $request->get('productname');
        $totalprice = $request->get('total');
        $two0 = "00";
        $total = "$totalprice$two0";
        $stripe = new \Stripe\StripeClient('sk_test_51OR7lnSBJGtnuEwkV7ZaYdDnYnQQBqpyPmrLgtepPmOgP3kEiqOSG2MEhYOpoTWqOoPWJnGYvjFmZqKs4bTtX8a000L0msQcYV');

$stripe->paymentIntents->create([
  'amount' => 1099,
  'currency' => 'usd',
  'description' => 'Software development services',
]);
 
        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'INR',
                        'product_data' => [
                            "name" => $productname,
                        ],
                        'unit_amount'  => '700',
                    ],
                    'quantity'   => 1,
                ],
                 
            ],
            'mode'        => 'payment',
            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);
 
        return redirect()->away($session->url);
    }
 
    public function success()
    {
        return "Thanks for you order You have just completed your payment. The seeler will reach out to you as soon as possible";
    }


    //Steps

    // First method to get the response  of webhook

    public function webhook(Request $request)
    {
        try {
            // Get details from the request body
            $data = $request->json()->all();
         //   print_r($data);
          $amount = ($data['data']['object']['amount']);
          DB::table('users')->insert(
            ['name' => $amount]
        );
            
            if ($data) {
                // If JSON data is successfully decoded
                return response()->json(['message' => 'success', 'data' => $data], 201); // 201 Created
            } else {
                // If JSON decoding fails or no data received
                return response()->json(['message' => 'error', 'data' => 'Failed to insert price checks details'], 500); // 500 Internal Server Error
            }
        } catch (\Exception $e) {
            // Handle exceptions (errors)
            \Log::error("Exception: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500); // 500 Internal Server Error
        }
    }
// Second method to get the response  of webhook

    public function webhook(Request $request)
    {
        $response = file_get_contents('php://input');
        $data = json_decode($response);
       // print_r($data);
        $amount = $data->data->object->amount;
        print_r($amount);
               DB::table('users')->insert(
            ['name' => $amount]
        );
    }

    //Third method to get the response  of webhook

    public function webhook(Request $request)
    {
        $payload = $request->all(); 
        print_r($payload);
    }
 }

   


    
