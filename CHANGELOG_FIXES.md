# تقرير إصلاح مشروع Car Rental

## النتيجة النهائية

- `php artisan test`: **9 اختبارات ناجحة، 18 assertion ناجحة**.
- `php artisan migrate:fresh --seed --force` على SQLite: **نجح بالكامل**.
- فحص syntax لكل ملفات PHP: **لا توجد أخطاء syntax**.
- `composer dump-autoload`: **نجح دون تحذيرات PSR-4**.
- `php artisan route:list`: **27 route مسجلة بنجاح**.

> ملاحظة: ملف ZIP الأصلي لا يحتوي على مجموعة الاختبارات الخارجية المكوّنة من 34 اختبارًا المذكورة في تقرير المراجعة؛ كان يحتوي فقط على اختبار Laravel الافتراضيين. لذلك أضفت اختبارات تكامل محلية تغطي أهم بنود التقرير، ولا أدّعي تشغيل الاختبارات الخارجية نفسها لأنها غير موجودة في المشروع المرسل.

## التعديلات الرئيسية

### 1. إصلاح PSR-4 وNamespaces

- نقل `app/Exception/BookingException.php` إلى `app/Exceptions/BookingException.php`.
- نقل طلبات الإدارة الخمسة إلى `app/Http/Requests/Admin/`:
  - `StoreVehicleRequest.php`
  - `UpdateVehicleRequest.php`
  - `UpdateVehicleStatusRequest.php`
  - `StoreMakeRequest.php`
  - `StoreCarModelRequest.php`
- نقل `BookingCarResource.php` إلى `app/Http/Resources/Rental/` بما يطابق namespace.
- النتيجة: اختفت تحذيرات Composer الخاصة بالـautoload.

### 2. الحجز

- إضافة constructor وdependency injection صحيح لـ`BookingCarController`.
- إصلاح `RentalResource` ليستخدم أعمدة الحجز الفعلية:
  - `rental_start_date`
  - `rental_end_date`
  - `initial_total_due_amount`
  - `vehicleReturn`
- تمرير `initial_check_notes` من طلب الحجز بدل قراءته من السيارة.
- عدم تحويل السيارة إلى `rented` لحجز يبدأ في المستقبل؛ تبقى `available` حتى موعد البداية.
- الإبقاء على منع تعارض الحجوزات وإرجاع `409` بدل `500`.

### 3. المركبات والـLookups

- تصحيح فلتر `drive_type_id`.
- تصحيح علاقة الصور من `images` إلى علاقة `carImages`/العلاقة الموحدة `images`، وقراءة العمود `car_image` الصحيح.
- تصحيح علاقة فئة السيارة إلى `category`.
- دعم أسماء API الحالية `car_model_id` و`daily_rate` مع تحويلها داخليًا إلى أعمدة قاعدة البيانات القديمة `model_id` و`rental_price_per_day`.
- إضافة `seats` و`transmission` إلى migration المركبات.
- إضافة التحقق الإلزامي من `kilo_meterage` عند إنشاء المركبة وتحديثها.
- إضافة accessors لتوحيد اسم `name` مع أعمدة الـlookups القديمة:
  - `fuel_name`
  - `type_name`
  - `catygory_name`
- إصلاح استعلامات `VehicleLookupService` لتستخدم أسماء الأعمدة الحقيقية.

### 4. العلاقات وModels

- تحويل علاقة `CarModel -> Vehicle` إلى `hasMany` مع إبقاء alias للتوافق.
- إضافة علاقة `Make -> models` مع إبقاء alias القديم.
- تصحيح علاقة `RentalTransaction -> RentalBooking` لاستخدام `booking_id`.
- إصلاح `CustomerResource` ليقرأ بيانات العميل من علاقة `User -> Customer` بدل إرجاع القيم `null`.
- إصلاح `CRUDAdminService` ليعيد Model متوافقًا مع `AdminResource` بدل مصفوفة غير قابلة للعرض.

### 5. المسارات والحماية

- حماية `POST /api/auth/register/partner` بواسطة `auth:sanctum` و`is_Admin` بدل تركه عامًا.
- إضافة مسارات ملف الأدمن:
  - `GET /api/admin/profile`
  - `PUT /api/admin/profile`
- إضافة مسارات العميل لتصفح السيارات:
  - `GET /api/customer/vehicles`
  - `GET /api/customer/vehicles/{id}`
- إضافة مسار تغيير كلمة المرور:
  - `PUT /api/auth/password`
- إصلاح `ChangePasswordController` ليستخدم `Controller` بالـcase الصحيح على Linux.
- إصلاح خدمة تغيير كلمة المرور لتتحقق من `old_password` الصحيح.
- إضافة انتهاء افتراضي لتوكنات Sanctum مدته 60 دقيقة، قابل للتعديل عبر `SANCTUM_TOKEN_EXPIRATION`.

### 6. Validation وقاعدة البيانات

- إصلاح unique validation في تحديث الأدمن والعميل ليستثني السجل الحالي.
- جعل الأعمدة المحسوبة للحجز متوافقة مع MySQL وSQLite:
  - MySQL يستخدم `DATEDIFF`.
  - SQLite يستخدم `julianday`.
- تغيير علاقات الحجوزات والإرجاعات والمعاملات والصور إلى `restrictOnDelete` لحماية السجل المالي من الحذف المتسلسل.
- إصلاح Namespace للـseeders في `DatabaseSeeder`.
- إصلاح أسماء حقول `MakeSeeder` و`CarModel` لتستخدم `name` بدل `make_name` و`model_name`.
- إصلاح Mass Assignment لموديل `CarCategory`.

## ملفات الاختبار المضافة

- `tests/Feature/CarRentalIntegrationTest.php`

وتغطي حماية تسجيل الشريك، الـlookups، إنشاء المركبة، عرض السيارات للعميل، الحجز والتعارض، تحديث العميل، وتغيير كلمة المرور.
