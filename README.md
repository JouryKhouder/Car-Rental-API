#  Car Rental Management System - RESTful API

A comprehensive backend system for a car rental platform built with Laravel, utilizing Clean Architecture (Service-Layer Pattern) to ensure separation of concerns, scalability, and robust business logic.

---

##  Key Architectural Highlights

* **Service-Controller Pattern:** Complete separation of business logic into dedicated service classes, structured by domain (`Admin`, `Auth`, `Customer`, `Vehicle`) with implemented contracts (Interfaces).
* **Concurrency & Deadlock Prevention:** Implemented Pessimistic Locking inside database transactions (`DB::transaction`) during vehicle booking and returns to prevent race conditions and double-booking.
* **Data Validation & Transformation:** Comprehensive use of `FormRequests` for strict input validation and `API Resources` to standardize JSON responses.
* **Role-Based Access Control (RBAC):** Secured endpoints using Laravel Sanctum and a custom middleware (`CheckRole`) to distinguish between Admin and Customer permissions.

---

##  Implemented Features

* **Vehicle & Media Management:** Complete CRUD operations for vehicles, including specifications, real-time availability status, and vehicle image upload handling.
* **Booking & Rental Engine:** Safe vehicle reservation for specific dates with concurrency protection and automatic initial cost estimation.
* **Car Return & Accounting Workflow:** Accurate financial lifecycle including a 50% initial deposit requirement, dynamic calculation of actual rental days, consumed mileage, late/mileage penalties, and final settlement upon vehicle return.
* **Automated Email Notifications:** Event-driven email notification system (welcome alerts, login security alerts, rental confirmations, return confirmations, and payment receipts) using custom Blade templates.
* **Authentication & Security:** Token-based authentication via Laravel Sanctum with role-specific route protection.

---

##  Future Improvements / Roadmap

* [ ] **Payment Gateway Integration:** Integrate Stripe/PayPal for automated online deposit and settlement payments.
* [ ] **PDF Invoice Generation:** Automatically generate and attach downloadable PDF rental contracts and final receipts to emails.
* [ ] **Advanced Caching:** Implement Redis caching for frequently accessed vehicle listings and filtering queries.
* [ ] **API Documentation:** Generate interactive Swagger / OpenAPI documentation for all endpoints.
* [ ] **Admin Analytics Dashboard:** Endpoints for monthly revenue reports, fleet utilization rates, and top-rented vehicles.

---

##  Tech Stack

* **Framework:** Laravel 11.x / PHP 8.2+
* **Database:** MySQL / SQLite
* **Authentication:** Laravel Sanctum (Bearer Token)
* **Architecture:** Service-Repository / Contract Pattern
* **Testing:** PHPUnit (Feature & Integration Tests)

---

##  Project Structure

```text
app/
├── Exceptions/         # Custom exceptions (BookingException)
├── Http/
│   ├── Controllers/    # Domain-based controllers (Admin, Auth, Customer, Vehicle)
│   ├── Middleware/     # Authorization middleware (CheckRole)
│   ├── Requests/       # Validation form requests
│   └── Resources/      # API response formatting
├── Mail/               # Email mailables (Auth, Payment, Rental)
├── Models/             # Eloquent models and relationships
└── Services/           # Business logic and contracts
