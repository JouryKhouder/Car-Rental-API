@extends('emails.layouts.master')

@section('content')
    <h2 style="color: #0f172a; margin-top: 0;">أهلاً بك، {{ $user->first_name }}! 👋</h2>

    <p>يسعدنا جداً انضمامك إلى منصتنا لتأجير السيارات. تم إنشاء حسابك بنجاح ويمكنك الآن تصفح أسطول السيارات المتاح لدينا واختيار السيارة التي تناسب رحلتك القادمة بكل سهولة.</p>

    <div style="background-color: #f1f5f9; border-right: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;">
        <p style="margin: 0; font-weight: bold; color: #1e293b;">معلومات الحساب المسجل:</p>
        <p style="margin: 5px 0 0 0;"><strong>الاسم الكامل:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
        <p style="margin: 5px 0 0 0;"><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
    </div>

    <p>نتمنى لك تجربة قيادة آمنة وممتعة معنا!</p>
@endsection
