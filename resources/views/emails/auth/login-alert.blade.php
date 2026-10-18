@extends('emails.layouts.master')

@section('content')
    <h2 style="color: #0f172a; margin-top: 0;">تنبيه أمني: تسجيل دخول جديد 🔐</h2>

    <p>مرحباً <strong>{{ $user->first_name }}</strong>،</p>
    <p>تم تسجيل الدخول إلى حسابك للتو. إليك تفاصيل العملية:</p>

    <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0;">
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 35%;">التاريخ والوقت:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $loginTime }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">عنوان الـ IP:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $ipAddress ?? 'غير محدد' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">المتصفح / الجهاز:</td>
            <td>{{ $userAgent ?? 'تطبيق الموبايل / API' }}</td>
        </tr>
    </table>

    <p style="color: #dc2626; font-size: 14px;">
        <strong>ملاحظة هامة:</strong> إذا لم تكن أنت من قام بتسجيل الدخول هذا، يرجى المبادرة فوراً إلى تغيير كلمة المرور الخاصة بك لحماية حسابك.
    </p>
@endsection
