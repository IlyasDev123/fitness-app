<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
        }

        .content {
            font-size: 14px;
        }

        .greeting {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .code {
            font-size: 18px;
            font-weight: bold;
        }

        .instructions {
            margin-bottom: 20px;
        }

        .contact {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
            color: #777777;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
            color: #777777;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0" align="center">
        <tbody style="
    background: #FFFFFF;
    border-radius: 6px;
">
            <tr>
                <td>
                    <table class="logo" cellpadding="0" cellspacing="0" border="0" align="center"
                        style="background-color: #1B1D21;width: 100%;padding: 38px 0px;border-radius: 6px 6px 0px 0px;">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="https://dev.upworkdeveloper.com/new/easytask/public/assets/images/logo.png"
                                        alt="Logo">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="content" style="text-align: center;">
                    <p class="greeting"
                        style="
                    font-family: 'Poppins';
                    font-style: normal;
                    font-weight: 600;
                    font-size: 32px;
                    line-height: 48px;
                    text-align: center;
                    letter-spacing: -0.5px;
                    color: #11142D;">
                        Hi 👋</p>
                    <p
                        style="
                    font-family: 'Inter';
                    font-style: normal;
                    font-weight: 600;
                    font-size: 16px;
                    line-height: 19px;
                    text-align: center;
                    color: #808191;">
                        Your email verification code is <span class="code"
                            style="
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    font-size: 48px;
    line-height: 72px;
    text-align: center;
    letter-spacing: -1px;

/* Main Colors/Primary */
    color: #DEAC00;
    display: block;
    margin-top: 30px;
">{!! $data['otp'] !!}</span>.
                    </p>
                    <p
                        style="
    font-family: 'Inter';
    font-style: normal;
    font-weight: 400;
    font-size: 14px;
    line-height: 24px;
/* or 171% */
    text-align: center;

/* text/light */
    color: #808191;
    border-bottom: 1px solid #CCCCCC;
    position: relative;
    max-width: 460px;
    margin: 0px auto;
    /* padding: 0px 50px; */
    margin: 0px 70px;
    padding-bottom: 40px;
    margin-bottom: 40px;
">
                        All you have to do is to copy the confirmation code and paste it to your form to complete the
                        email verification
                        process.</p>
                    <p
                        style="
    font-family: 'Inter';
    font-style: normal;
    font-weight: 400;
    font-size: 14px;
    line-height: 24px;
/* or 171% */
    text-align: center;

/* text/light */
    color: #808191;
    margin: 0px;
">
                        This email is automatically sent and doesn’t receive responses.</p>
                    <p
                        style="
    font-family: 'Inter';
    font-style: normal;
    font-weight: 400;
    font-size: 14px;
    line-height: 24px;
/* or 171% */
    text-align: center;

/* text/light */
    color: #808191;
    margin: 0;
">
                        Need help? Contact us at <a href="mailto:contact@EasyTask.com"
                            style="
    color: #DEAC00;
    text-decoration: none;
">contact@EasyTask.com</a>.</p>
                    <p class="footer"
                        style="
    background: #FDF8EA;
    border-radius: 0px 0px 6px 6px;
    padding: 18px 0px;
    margin: 0;
    margin-top: 50px;
">
                        © Copyright EasyTask 2023, All Rights Reserved.</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
