@extends('emails.layouts.master')

@section('content')
    <h2 style="color: #16a34a; margin-top: 0;">تأكيد حجز السيارة 🚗</h2>

    <p>مرحباً <strong>{{ $user->first_name }}</strong>،</p>
    <p>تم تأكيد طلبك لاستعارة السيارة بنجاح. فيما يلي ملخص الحجز الخاص بك:</p>

    <table width="100%" cellpadding="12" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin: 20px 0;">
        <tr style="background-color: #e2e8f0;">
            <th colspan="2" style="text-align: right; color: #1e293b;">تفاصيل السيارة والحجز (#{{ $rental->id }})</th>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">السيارة:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">
                {{ $vehicle->carModel->make->name }} - {{ $vehicle->carModel->name }} ({{ $vehicle->year }})
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">رقم اللوحة:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $vehicle->plate_number }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">تاريخ الاستلام:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $rental->rental_start_date }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">تاريخ الإرجاع المتوقع:</td>
            <td style="border-bottom: 1px solid #e2e8f0;">{{ $rental->rental_end_date }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; color: #0f172a;">التكلفة الإجمالية:</td>
            <td style="font-weight: bold; color: #16a34a; font-size: 18px;">${{ number_format($rental->initial_total_due_amount, 2) }}</td>
        </tr>
    </table>

    <p>يرجى اصطحاب رخصة القيادة السارية والبطاقة الشخصية عند الحضور لاستلام السيارة.</p>
@endsection
