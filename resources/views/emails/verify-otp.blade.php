<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Shivara</title>
</head>
<body style="margin:0; padding:0; background-color:#FFFDF8; font-family:'Helvetica Neue',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFFDF8; padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:500px; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(44,36,24,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#2C2418; padding:30px; text-align:center;">
                            <h1 style="margin:0; color:#FFF9ED; font-size:28px; font-weight:700; letter-spacing:2px;">SHIVARA</h1>
                            <p style="margin:5px 0 0; color:#D4B078; font-size:12px; letter-spacing:1px;">AYURVEDIC PURITY, ELEVATED</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:40px 30px;">
                            <p style="color:#4A3828; font-size:16px; margin:0 0 10px;">Hello {{ $name ?? 'there' }},</p>
                            <p style="color:#6B5442; font-size:14px; line-height:1.6; margin:0 0 25px;">
                                Thank you for creating your Shivara account! Please verify your email address using the code below:
                            </p>

                            <!-- OTP Box -->
                            <div style="text-align:center; margin:30px 0;">
                                <div style="display:inline-block; background-color:#F5F7F2; border:2px dashed #A8BA94; border-radius:12px; padding:20px 40px;">
                                    <p style="margin:0 0 5px; color:#6B5442; font-size:12px; text-transform:uppercase; letter-spacing:1px;">Your Verification Code</p>
                                    <p style="margin:0; color:#2C2418; font-size:36px; font-weight:700; letter-spacing:8px;">{{ $otp }}</p>
                                </div>
                            </div>

                            <p style="color:#8C7560; font-size:13px; line-height:1.6; margin:0 0 5px;">
                                This code is valid for <strong>10 minutes</strong>.
                            </p>
                            <p style="color:#8C7560; font-size:13px; line-height:1.6; margin:0;">
                                If you didn't create an account with Shivara, please ignore this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#F5F7F2; padding:20px 30px; text-align:center; border-top:1px solid #E8EDE2;">
                            <p style="color:#8C7560; font-size:12px; margin:0;">
                                Need help? Contact us at <a href="mailto:shop@theshivara.com" style="color:#5C7A44;">shop@theshivara.com</a>
                            </p>
                            <p style="color:#B8A594; font-size:11px; margin:10px 0 0;">
                                &copy; {{ date('Y') }} Shivara. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
