<?php

namespace App\Http\Controllers;

use App\Mpesa;
use Exception;
use App\Result;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    /**
     * 
     */
    private $access_token;


    public function __construct()
    {
        $url = env('MPESA_URL', '');
        $consumer_key = env('MPESA_CONSUMER_KEY', '');
        $consumer_secret = env('MPESA_CONSUMER_SECRET', '');
        $client = new Client();
        $request = $client->get($url, [
            'auth' => [
                $consumer_key, 
                $consumer_secret
            ]
        ]);
        $body = $request->getBody();
        // echo $body->getContents(); // -->nothing

        // Rewind the stream
        $body->rewind();
        $this->access_token = json_decode($body->getContents(), true)['access_token'];
    }

    /**
     * Update the specified resource in storage.
     *
     * 
     */
    public function make_payment()
    {
        $client = new Client();
        $time = date('YmdHms');
        $shortcode = "174379";
        $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $password = base64_encode($shortcode . $passkey . $time);

        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,   
            'Content-Type' => 'application/json', 
            'Host: sandbox.safaricom.co.ke'
        ];
        $data = [
            "BusinessShortCode" => $shortcode,
            "Password" => $password,
            "Timestamp" => $time,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => "1",
            "PartyA" => "254723077827",
            "PartyB" => $shortcode,
            "PhoneNumber" => "254723077827",
            "CallBackURL" => "https://8d14c271.ngrok.io/api/payment-result",
            "AccountReference" => "Destow!",
            "TransactionDesc" => "Testing Sandbox"
        ];
        try {
            $request = $client->request('POST', $url   , [
                'headers' => $headers,
                'json' => $data,
            ]);
            $response = $request->getBody()->getContents();
            dd($response);
        }
        catch (Exception $e) {
            // if($e->getMessage() == ''){

            // }
        }
        
    }

    
    public function payment_result(Request $request)
    {
        $result = Result::create([
            'result' => $request
        ]);
    }
}
