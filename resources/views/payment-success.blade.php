<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed</title>
    <script>
        // Redirect to the home screen after 5 seconds
        setTimeout(function() {
            window.location.href = "{{ route('dashboard') }}"; // Update 'home' with your actual home route name
        }, 5000);
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
            text-align: center;
        }
        .message {
            border: 1px solid green;
            background-color: #ffebee;
            color: green;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="message">
        <h1>Payment Confirmed</h1>
        <p>{{ session('Succes', 'Your payment has been Confirmed.') }}</p>
        <p>You will be redirected to the home screen shortly.</p>
    </div>
</body>
</html>
