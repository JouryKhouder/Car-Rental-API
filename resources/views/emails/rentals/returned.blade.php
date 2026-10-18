@extends('emails.layouts.master')

@section('content')
    <h2 style="color: #0f172a; margin-top: 0;">تم استلام السيارة بنجاح شكراً لك! 🤝</h2>

    <p>مرحباً <strong>{{ $user->first_name }}</strong>،</p>
    <p>نؤكد لك أنه قد تم تسجيل إرجاع السيارة <strong>({{ $vehicle->carModel->make->name }} {{ $vehicle->carModel->name }})</strong> وإنهاء عقد الاستعارة بنجاح.</p>

    <table width="100%" cellpadding="12" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin: 20px 0;">
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">رقم العقد:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">#{{ $rental->id }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">تاريخ الإرجاع الفعلي:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $rental->actual_return_date ?? now()->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">المبلغ النهائي المسدد:</td>
            <td style="font-weight: bold; color: #2563eb;">${{ number_format($rental->total_price, 2) }}</td>
        </tr>
    </table>

    <p>سعدنا جداً بخدمتك، ونتطلع لرؤيتك مجدداً في رحلاتك القادمة!</p>
@endsection
