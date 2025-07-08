<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000; /* Black background */
            margin: 0;
            padding: 0;
            color: #fff; /* White text */
        }

        .container {
            background: #1a1a1a; /* Dark gray background for the email container */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
            max-width: 600px;
            margin: 30px auto;
            border: 1px solid #444; /* Slightly lighter border */
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header img {
            width: 150px; /* Logo size */
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            color: #4CAF50; /* Green text */
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #fff; /* White text for the OTP */
            text-align: center;
            border: 3px solid #4CAF50; /* Green border */
            background-color: #333; /* Dark background for OTP */
            padding: 15px;
            border-radius: 5px;
            margin: 30px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        p {
            line-height: 1.6;
            font-size: 16px;
            color: #ccc; /* Light gray text for paragraphs */
            margin: 10px 0;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #444; /* Dark border */
            padding-top: 20px;
        }

        .contact-info {
            margin-top: 10px;
        }

        .contact-info p {
            margin: 5px 0;
        }

        a {
            color: #4CAF50; /* Green links */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Additional styling for responsiveness */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .otp-code {
                font-size: 28px;
                padding: 10px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://mobiplay.mx/assets/img/logo.png" alt="Mobiplay Logo">
            <h1>Your OTP Code</h1>
        </div>
        <p>Dear {{ $fullName }},</p>
        <p>Your one-time password (OTP) is:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>Please use this code to complete your verification within the next 10 minutes.</p>
        <p>Thank you for choosing our service!</p>
        <div class="footer">
            <div class="contact-info">
                <p><strong>Contact Us:</strong></p>
                <p>Av. Paseo Monte Miranda Ote. 15,<br>
                Monte Miranda, 76246 Santiago de Querétaro,<br>
                Qro., Mexico</p>
                <p>Email: <a href="mailto:contacto@mobiplay.com.mx">contacto@mobiplay.com.mx</a></p>
            </div>
        </div>
    </div>
</body>

</html>
