@extends('emails.layouts.master')

@section('content')
    <h2 style="color: #0f172a; margin-top: 0;">تم تحديث كلمة المرور بنجاح ✅</h2>

    <p>مرحباً <strong>{{ $user->first_name }}</strong>،</p>
    <p>نود إعلامك بأنه قد تم تغيير كلمة المرور الخاصة بحسابك بتاريخ <strong>{{ now()->format('Y-m-d H:i') }}</strong>.</p>

    <div style="background-color: #fef2f2; border-right: 4px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 4px; color: #991b1b;">
        إذا كنت أنت من قام بهذا الإجراء، فيمكنك تجاهل هذه الرسالة بأمان. أما إذا لم تقم بذلك، فيرجى التواصل مع فريق الدعم الفني فوراً.
    </div>
@endsection
