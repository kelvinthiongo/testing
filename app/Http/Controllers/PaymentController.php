<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
// use Paypal\Api\OAuthTokenCredential;
use PayPal\Auth\OAuthTokenCredential;
// use Paypal\Rest\Api\ApiContext;
use PayPal\Rest\ApiContext;


class PaymentController extends Controller
{
    private $_api_context;

    public function __construct(){
        $paypal_config = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_config['client_id'],
            $paypal_config['secret'],
        ));
        $this->_api_context->setConfig($paypal_config['settings']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paywithpaypal(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1')->setCurrency('USD')
                                  ->setQuantity(1)
                                  ->setPrice($request->amount);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($request->amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Your Transaction Description');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status'))->setCancelUrl(URL::to('status'));
        $payment = new Payment();
        $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls('Redirect Urls')->setTransactions(array($transaction));
        dd($this->_api_context);
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $e) {
            if(\Config::get('app.debug')){
                \Session::put('error', 'Connection  timeout');
                return Redirect::to('/');
            }
            else{
                \Session::put('error', 'Some error occured, sorry for the inconvinience.');
                return Redirect::to('/');
            }
        }
    }

    
    public function getPaymentStatus($id)
    {
        $payment_id = Session::get('paypal_payment_id');
        Session::forget('paypal_payment_id');
        if(empty(Input::get('PayerID')) || empty(Input::get('token'))){
            \Session::put('error', 'Payment failed');
            return Redirect::to('/');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if($resultgetState() == 'approved'){
            \Session::put('success', 'Payment success');
            return Redirect::to('/');
        }
        \Session::put('error', 'Payment failed');
        return Redirect::to('/');
    }
}
