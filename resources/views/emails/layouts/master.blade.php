<!DOCTYPE html>
<html lang="ar" dir="rtl" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ config('app.name', 'Car Rental') }}</title>
    <style>
        /* CSS Reset لتنظيف الفراغات الافتراضية في مختلف تطبيقات الإيميل */
        table, td { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }

        /* Media Query لتجاوب الإيميل مع شاشات الموبايل */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; border-radius: 0 !important; }
            .content-padding { padding: 25px 15px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; direction: rtl; text-align: right; -webkit-font-smoothing: antialiased;">

    <!-- الجدول الحاوي للخلفية الرمادية -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f7f6; padding: 20px 10px;">
        <tr>
            <td align="center">

                <!-- بداية الوسوم الخاصة ببرنامج Outlook المكتبي -->
                <!--[if mso]>
                <table role="presentation" align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                <td align="center" valign="top" width="600">
                <![endif]-->

                <!-- الجدول الأساسي للمحتوى (مرن ومطاطي) -->
                <table class="email-container" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin: auto;">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 25px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px;">
                                {{ config('app.name', 'Car Rental System') }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content-padding" style="padding: 35px 30px; color: #334155; line-height: 1.8; font-size: 16px;">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 13px;">
                            <p style="margin: 0 0 8px 0;">هذه رسالة تلقائية من نظام {{ config('app.name', 'Car Rental') }}، يرجى عدم الرد على هذا البريد.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} جميع الحقوق محفوظة.</p>
                        </td>
                    </tr>

                </table>

                <!-- نهاية الوسوم الخاصة ببرنامج Outlook -->
                <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->

            </td>
        </tr>
    </table>
</body>
</html>
