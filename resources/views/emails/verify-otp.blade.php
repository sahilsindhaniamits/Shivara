<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Verify Email</title></head>
<body style="margin:0;padding:0;background:#FFFDF8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px;">
<tr><td align="center">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:500px;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(44,36,24,0.08);">
<tr><td style="background:#2C2418;padding:30px;text-align:center;">
<h1 style="margin:0;color:#FFF9ED;font-size:28px;font-weight:700;letter-spacing:2px;">SHIVARA</h1>
<p style="margin:5px 0 0;color:#D4B078;font-size:12px;letter-spacing:1px;">AYURVEDIC PURITY, ELEVATED</p>
</td></tr>
<tr><td style="padding:40px 30px;">
<p style="color:#4A3828;font-size:16px;">Hello {{ $name ?? 'there' }},</p>
<p style="color:#6B5442;font-size:14px;line-height:1.6;">Please verify your email with the code below:</p>
<div style="text-align:center;margin:30px 0;">
<div style="display:inline-block;background:#F5F7F2;border:2px dashed #A8BA94;border-radius:12px;padding:20px 40px;">
<p style="margin:0 0 5px;color:#6B5442;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Your Code</p>
<p style="margin:0;color:#2C2418;font-size:36px;font-weight:700;letter-spacing:8px;">{{ $otp }}</p>
</div>
</div>
<p style="color:#8C7560;font-size:13px;">Valid for <strong>10 minutes</strong>. If you didn't register, ignore this.</p>
</td></tr>
<tr><td style="background:#F5F7F2;padding:20px 30px;text-align:center;border-top:1px solid #E8EDE2;">
<p style="color:#8C7560;font-size:12px;margin:0;">&copy; {{ date('Y') }} Shivara. All rights reserved.</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
