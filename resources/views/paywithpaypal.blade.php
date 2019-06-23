<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>Pay With Paypal</title>
</head>

<body>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-4">
                @if($message == Session::get('success'))
                    <div class="panel success">
                        {{ $message }}
                    </div>
                    <?php Session::forget('success') ?>
                @endif
                @if($message == Session::get('error'))
                    <div class="panel danger">
                        {{ $message }}
                    </div>
                    <?php Session::forget('error') ?>
                @endif
                <form action="{!! URL::to('paypal') !!}" class="" method="POST">
                    <div class="title">Pay With Paypal</div>
                    @csrf
                    <h2>Payment Form</h2>
                    <p>Demo Paypal Form Demonstrating payment with paypal</p>
                    <div class="form-group">
                        <label for="amount">Enter Amount:</label>
                        <input type="amount" class="form-control" id="amount">
                    </div>
                    <button type="submit" class="btn btn-primary">Pay with Paypal</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>