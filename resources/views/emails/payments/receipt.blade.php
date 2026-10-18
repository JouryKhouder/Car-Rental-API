@extends('emails.layouts.master')

@section('content')
    <h2 style="color: #16a34a; margin-top: 0;">إشعار استلام دفعة مالية 💳</h2>

    <p>مرحباً <strong>{{ $user->first_name }}</strong>،</p>
    <p>نشكرك على الدفع. نؤكد لك استلام المبلغ بنجاح وقيده على حساب عقد التأجير الخاص بك. إليك تفاصيل الإيصال المالي:</p>

    <!-- بطاقة تفاصيل الإيصال -->
    <table width="100%" cellpadding="12" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin: 20px 0;">
        <tr style="background-color: #e2e8f0;">
            <th colspan="2" style="text-align: right; color: #1e293b;">إيصال دفع (#{{ $payment->transaction_id ?? $payment->id }})</th>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">رقم العقد / الحجز:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">#{{ $rental->id }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">نوع الدفعة:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">
                <span style="display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 13px; font-weight: bold; background-color: #e0f2fe; color: #0369a1;">
                    {{ $paymentTypeLabel }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">طريقة الدفع:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $payment->payment_method ?? 'بطاقة ائتمان / كاش' }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">تاريخ ووقت العملية:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a;">المبلغ المدفوع:</td>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #16a34a; font-size: 18px;">
                ${{ number_format($payment->amount, 2) }}
            </td>
        </tr>
        @if(isset($remainingBalance) && $remainingBalance > 0)
        <tr>
            <td style="font-weight: bold; color: #b91c1c;">المبلغ المتبقي للعقد:</td>
            <td style="font-weight: bold; color: #b91c1c; font-size: 16px;">
                ${{ number_format($remainingBalance, 2) }}
            </td>
        </tr>
        @else
        <tr>
            <td style="font-weight: bold; color: #15803d;">حالة الحساب:</td>
            <td style="font-weight: bold; color: #15803d;">تم تسديد كامل المبلغ المستحق بنجاح ✅</td>
        </tr>
        @endif
    </table>

    <div style="background-color: #f1f5f9; border-right: 4px solid #64748b; padding: 12px; margin: 15px 0; border-radius: 4px; font-size: 14px; color: #475569;">
        السيارة المرتبطة بالعقد: <strong>{{ $vehicle->carModel->make->name }} {{ $vehicle->carModel->name }} ({{ $vehicle->plate_number }})</strong>
    </div>

    <p>يمكنك الاحتفاظ بهذا البريد الإلكتروني كإيصال رسمي للدفعة المسددة.</p>
@endsection
