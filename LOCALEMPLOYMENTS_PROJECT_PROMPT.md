# LOCALEMPLOYMENTS — Full Project Prompt for Antigravity IDE

> **Dear AI (Antigravity),** read this entire document before writing a single line of code.
> This is the complete blueprint for the `LocalEmployments` project. Every architectural decision,
> database schema, folder structure, UI guideline, and feature specification is defined here.
> Follow this document strictly. Do not improvise or add packages not listed here.

---

## ═══════════════════════════════════════
## SECTION 1 — CORE RULES (NON-NEGOTIABLE)
## ═══════════════════════════════════════

### 🚫 Rule 1 — Admin Panel (CRITICAL)
- The Admin Panel must be **100% Custom Code** built from scratch.
- Do **NOT** use Filament, Nova, Backpack, or any other pre-built admin panel package.
- Build all admin controllers, Blade views, authentication, and routing manually.
- Admin has its own layout, its own auth guard, its own middleware.

### 🎨 Rule 2 — Frontend Design Standard
- The frontend must be **exceptionally beautiful, modern, and premium**.
- Use **smooth micro-animations**, engaging hover effects, premium typography.
- Do **not** produce simple, basic, or generic MVP designs.
- Follow the design system defined in Section 7 of this document exactly.
- Every page must feel like a ৳10,000/month SaaS product, not a student project.

### ⚙️ Rule 3 — Code Quality
- Use **Laravel best practices**: Form Requests for validation, Service classes for business logic, Policies for authorization.
- Every controller method must be thin — logic goes in Service classes.
- Use **named routes** everywhere. No hardcoded URLs in Blade.
- Use **Laravel migrations** for all DB changes. No raw SQL files.
- All user inputs must be sanitized and validated.

### 🔒 Rule 4 — Security
- All routes must have proper middleware (auth, role-check).
- File uploads: validate mime type + size, store in `storage/app/public` via `Storage` facade.
- Use CSRF tokens on all forms.
- Admin routes must be under a separate `admin` prefix with `IsAdmin` middleware.
- Providers cannot access seeker-only routes and vice versa.

### 📋 Rule 5 — Development Order
Build in the order defined in **Section 9 (Development Sequence)**. Do not skip ahead.
Complete each phase fully before moving to the next.

---

## ═══════════════════════════════════════
## SECTION 2 — PROJECT OVERVIEW
## ═══════════════════════════════════════

### What is LocalEmployments?

**Domain:** `localemployments.com`
**Type:** Local Service Marketplace (Bangladesh)
**Purpose:** Connect people who need home/local services with skilled workers in their area.

Think of it as the Bangladeshi alternative to Sheba.xyz — but with better UX, fairer pay for workers, and a system that actually works for both sides.

**The two sides of the marketplace:**

| Side | Who | Their Goal |
|---|---|---|
| **Service Seeker** | Homeowners, businesses | Get reliable services quickly at fair price |
| **Service Provider** | Plumbers, cleaners, tutors, etc. | Find consistent work, showcase skills, earn money |

**Core Promise:**
- For Seekers: "Find trusted help in your area, pay only when satisfied."
- For Providers: "Turn your skills into income — no idle time, no middlemen."

### Key Business Decisions (Already Made — Do Not Change)

| Decision | Choice | Reason |
|---|---|---|
| Booking model | BOTH: Bid system + Direct booking | Maximum flexibility for users |
| Real-time chat | Database polling (Alpine.js, every 5s) | cPanel-compatible, no Pusher needed |
| Location system | District + Thana dropdown (Bangladesh data) | No Google Maps API cost, works offline |
| Provider payout | Manual admin-approved withdrawal | Realistic for BD market, admin sends via bKash/bank |
| Mobile strategy | Responsive web only | Single codebase, no separate app yet |
| Commission rate | 12% of job value | Default, configurable from Admin settings |

---

## ═══════════════════════════════════════
## SECTION 3 — TECH STACK
## ═══════════════════════════════════════

```
Backend:       Laravel 11 (PHP 8.2+)
Frontend:      Blade + Tailwind CSS v3 + Alpine.js v3
Database:      MySQL 8.0+
Auth:          Laravel built-in Auth + custom OTP (BulkSMSBD)
Payment:       SSLCommerz (local gateway)
SMS/OTP:       BulkSMSBD API
File Storage:  Laravel Storage (local disk, symlinked)
Image:         Intervention Image v3 (resize/compress avatars)
Icons:         Heroicons (via Blade components) + custom SVGs
Fonts:         Inter (Google Fonts) — loaded in layout head
Animations:    Alpine.js transitions + custom CSS keyframes
Hosting:       cPanel shared hosting compatible
```

### Composer Packages (only these, nothing else)
```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0",
        "laravel/tinker": "^2.9",
        "intervention/image": "^3.0",
        "guzzlehttp/guzzle": "^7.8"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.26",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.0",
        "phpunit/phpunit": "^11.0"
    }
}
```

### NPM Packages
```json
{
    "devDependencies": {
        "@tailwindcss/forms": "^0.5",
        "@tailwindcss/typography": "^0.5",
        "alpinejs": "^3.13",
        "autoprefixer": "^10.4",
        "postcss": "^8.4",
        "tailwindcss": "^3.4",
        "vite": "^5.0",
        "laravel-vite-plugin": "^1.0"
    }
}
```

---

## ═══════════════════════════════════════
## SECTION 4 — DATABASE SCHEMA
## ═══════════════════════════════════════

Build migrations in this exact order. Each migration file is listed below.

---

### 4.1 `create_districts_table`
```
id               bigIncrements
name             string(100)          -- e.g. "Jashore", "Dhaka"
bn_name          string(100)          -- Bengali name
is_active        boolean default true
timestamps
```

### 4.2 `create_areas_table` (Thana/Upazila)
```
id               bigIncrements
district_id      foreignId → districts (cascade)
name             string(100)          -- e.g. "Kotwali", "Sadar"
bn_name          string(100)
is_active        boolean default true
timestamps
```

### 4.3 `create_users_table`
```
id               bigIncrements
name             string(150)
phone            string(20) unique    -- primary login identifier
email            string(191) nullable unique
password         string
role             enum('seeker','provider','admin') default 'seeker'
avatar           string nullable      -- path in storage
district_id      foreignId nullable → districts
area_id          foreignId nullable → areas
address          text nullable
otp              string(6) nullable
otp_expires_at   timestamp nullable
phone_verified   boolean default false
status           enum('active','suspended','banned') default 'active'
last_login_at    timestamp nullable
remember_token   string(100) nullable
timestamps
softDeletes
```

### 4.4 `create_service_categories_table`
```
id               bigIncrements
name             string(150)
slug             string(150) unique
icon             string(100)          -- icon identifier (e.g. "wrench", "broom")
description      text nullable
banner_image     string nullable
sort_order       unsignedSmallInt default 0
is_active        boolean default true
timestamps
```

### 4.5 `create_service_subcategories_table`
```
id               bigIncrements
category_id      foreignId → service_categories (cascade)
name             string(150)
slug             string(150) unique
description      text nullable
is_active        boolean default true
sort_order       unsignedSmallInt default 0
timestamps
```

### 4.6 `create_provider_profiles_table`
```
id               bigIncrements
user_id          foreignId unique → users (cascade)
bio              text nullable
experience_years unsignedTinyInt default 0
nid_number       string(20) nullable
profile_photo    string nullable
portfolio_photos json nullable        -- array of image paths
availability     json nullable        -- {"mon":true,"tue":true,...,"sun":false}
is_verified      boolean default false
verified_at      timestamp nullable
verification_status enum('pending','approved','rejected','not_submitted') default 'not_submitted'
rating_avg       decimal(3,2) default 0.00
total_reviews    unsignedInt default 0
total_jobs       unsignedInt default 0
is_featured      boolean default false
featured_until   timestamp nullable
hourly_rate_min  decimal(10,2) nullable
hourly_rate_max  decimal(10,2) nullable
response_rate    unsignedTinyInt default 100  -- percentage
timestamps
```

### 4.7 `create_provider_skills_table`
```
id               bigIncrements
provider_id      foreignId → users (cascade)  -- provider user_id
subcategory_id   foreignId → service_subcategories (cascade)
timestamps
unique(['provider_id','subcategory_id'])
```

### 4.8 `create_provider_verification_docs_table`
```
id               bigIncrements
provider_id      foreignId → users (cascade)
nid_front        string nullable      -- storage path
nid_back         string nullable
selfie_with_nid  string nullable
other_docs       json nullable        -- additional docs array
admin_note       text nullable
reviewed_by      foreignId nullable → users
reviewed_at      timestamp nullable
timestamps
```

### 4.9 `create_job_requests_table`
```
id               bigIncrements
seeker_id        foreignId → users (cascade)
subcategory_id   foreignId → service_subcategories
title            string(255)
description      text
district_id      foreignId → districts
area_id          foreignId → areas
address_detail   string(255) nullable  -- house/road detail
budget_min       decimal(10,2) nullable
budget_max       decimal(10,2) nullable
preferred_date   date nullable
preferred_time   string(10) nullable   -- e.g. "09:00", "14:00"
flexibility      enum('fixed','flexible','urgent') default 'flexible'
photos           json nullable         -- array of uploaded photo paths
status           enum('open','assigned','in_progress','completed','cancelled') default 'open'
expires_at       timestamp nullable    -- auto-close after 7 days
total_bids       unsignedInt default 0
timestamps
softDeletes
```

### 4.10 `create_job_bids_table`
```
id               bigIncrements
job_request_id   foreignId → job_requests (cascade)
provider_id      foreignId → users (cascade)
bid_amount       decimal(10,2)
message          text
estimated_hours  unsignedTinyInt nullable
status           enum('pending','accepted','rejected','withdrawn') default 'pending'
is_highlighted   boolean default false  -- paid highlight feature
timestamps
unique(['job_request_id','provider_id'])
```

### 4.11 `create_direct_services_table`
```
id               bigIncrements
provider_id      foreignId → users (cascade)
subcategory_id   foreignId → service_subcategories
title            string(255)
description      text
price            decimal(10,2)
price_type       enum('fixed','hourly','starting_from') default 'fixed'
estimated_duration string(100) nullable  -- "2-3 hours", "1 day"
service_areas    json                  -- array of area_ids covered
photos           json nullable
is_active        boolean default true
total_bookings   unsignedInt default 0
timestamps
```

### 4.12 `create_bookings_table`
```
id               bigIncrements
booking_type     enum('job_request','direct')  -- which type of booking
job_request_id   foreignId nullable → job_requests
bid_id           foreignId nullable → job_bids
direct_service_id foreignId nullable → direct_services
seeker_id        foreignId → users
provider_id      foreignId → users
booking_ref      string(20) unique    -- e.g. "LE-2024-00001"
service_date     date
service_time     string(10) nullable
location_detail  text nullable
service_amount   decimal(10,2)
platform_fee     decimal(10,2)        -- 12% commission
provider_earning decimal(10,2)        -- service_amount - platform_fee
seeker_note      text nullable
provider_note    text nullable
status           enum('pending','confirmed','in_progress','completed','disputed','cancelled','refunded') default 'pending'
confirmed_at     timestamp nullable
started_at       timestamp nullable
completed_at     timestamp nullable
cancelled_at     timestamp nullable
cancelled_by     foreignId nullable → users
cancel_reason    text nullable
timestamps
softDeletes
```

### 4.13 `create_payments_table`
```
id               bigIncrements
booking_id       foreignId → bookings (cascade)
seeker_id        foreignId → users
amount           decimal(10,2)
currency         string(5) default 'BDT'
payment_method   string(50) nullable   -- bkash, nagad, card, etc.
gateway          string(20) default 'sslcommerz'
transaction_id   string(100) nullable unique
val_id           string(100) nullable  -- SSLCommerz val_id for IPN
session_key      string(100) nullable  -- SSLCommerz session
gateway_response json nullable         -- full gateway response
status           enum('pending','processing','completed','failed','refunded') default 'pending'
paid_at          timestamp nullable
refunded_at      timestamp nullable
refund_ref       string(100) nullable
timestamps
```

### 4.14 `create_messages_table`
```
id               bigIncrements
booking_id       foreignId → bookings (cascade)
sender_id        foreignId → users (cascade)
receiver_id      foreignId → users (cascade)
message          text
attachment       string nullable      -- file path if any
is_read          boolean default false
read_at          timestamp nullable
timestamps
```

### 4.15 `create_reviews_table`
```
id               bigIncrements
booking_id       foreignId → bookings (cascade) unique
reviewer_id      foreignId → users (cascade)   -- who is reviewing
reviewee_id      foreignId → users (cascade)   -- who is being reviewed
rating           unsignedTinyInt               -- 1 to 5
comment          text nullable
is_visible       boolean default true          -- admin can hide
admin_note       text nullable
timestamps
```

### 4.16 `create_withdrawal_requests_table`
```
id               bigIncrements
provider_id      foreignId → users (cascade)
amount           decimal(10,2)
method           enum('bkash','nagad','bank') default 'bkash'
account_number   string(50)
account_name     string(150)
bank_name        string(150) nullable
branch_name      string(150) nullable
routing_number   string(50) nullable
status           enum('pending','processing','approved','rejected') default 'pending'
admin_note       text nullable
processed_by     foreignId nullable → users
processed_at     timestamp nullable
timestamps
```

### 4.17 `create_disputes_table`
```
id               bigIncrements
booking_id       foreignId → bookings (cascade)
raised_by        foreignId → users (cascade)
against          foreignId → users (cascade)
reason           string(255)
description      text
evidence_photos  json nullable
status           enum('open','under_review','resolved','closed') default 'open'
resolution       text nullable
resolved_by      foreignId nullable → users
resolved_at      timestamp nullable
timestamps
```

### 4.18 `create_notifications_table`
```
id               bigIncrements
user_id          foreignId → users (cascade)
title            string(255)
message          text
type             string(50)           -- 'booking','payment','bid','review','system'
data             json nullable        -- related model info
action_url       string(255) nullable
is_read          boolean default false
read_at          timestamp nullable
timestamps
```

### 4.19 `create_featured_subscriptions_table`
```
id               bigIncrements
provider_id      foreignId → users (cascade)
amount           decimal(10,2)
duration_days    unsignedSmallInt default 30
starts_at        timestamp
ends_at          timestamp
payment_id       foreignId nullable → payments
status           enum('active','expired','cancelled') default 'active'
timestamps
```

### 4.20 `create_settings_table`
```
id               bigIncrements
key              string(100) unique
value            text nullable
type             string(20) default 'string'  -- string, boolean, integer, json
group            string(50) default 'general'
description      text nullable
timestamps
```
**Default settings to seed:**
- `commission_rate` = `12` (percent)
- `featured_price_30days` = `500` (BDT)
- `featured_price_7days` = `150` (BDT)
- `min_withdrawal` = `200` (BDT)
- `max_bid_per_job` = `10`
- `job_request_expiry_days` = `7`
- `site_name` = `LocalEmployments`
- `site_tagline` = `আপনার এলাকায়, আপনার মানুষ`
- `contact_phone` = `` (empty, admin fills)
- `contact_email` = `` (empty, admin fills)
- `bulksms_api_key` = ``
- `sslcommerz_store_id` = ``
- `sslcommerz_store_password` = ``
- `sslcommerz_sandbox` = `true`

### 4.21 `create_admin_activity_logs_table`
```
id               bigIncrements
admin_id         foreignId → users
action           string(255)          -- e.g. "approved provider verification"
model_type       string(100) nullable
model_id         unsignedBigInt nullable
old_values       json nullable
new_values       json nullable
ip_address       string(45) nullable
timestamps
```

### 4.22 `create_sms_logs_table`
```
id               bigIncrements
phone            string(20)
message          text
type             string(50)           -- 'otp', 'booking', 'notification'
status           enum('sent','failed') default 'sent'
gateway_response json nullable
timestamps
```

---

## ═══════════════════════════════════════
## SECTION 5 — COMPLETE FOLDER STRUCTURE
## ═══════════════════════════════════════

```
localemployments/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── OtpController.php
│   │   │   │   └── LogoutController.php
│   │   │   │
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── SearchController.php
│   │   │   │   ├── ProviderPublicController.php
│   │   │   │   ├── ServiceCategoryController.php
│   │   │   │   └── PageController.php
│   │   │   │
│   │   │   ├── Seeker/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── JobRequestController.php
│   │   │   │   ├── BidManageController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   ├── DirectBookingController.php
│   │   │   │   ├── MessageController.php
│   │   │   │   ├── ReviewController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   ├── DisputeController.php
│   │   │   │   └── ProfileController.php
│   │   │   │
│   │   │   ├── Provider/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── VerificationController.php
│   │   │   │   ├── DirectServiceController.php
│   │   │   │   ├── JobBrowseController.php
│   │   │   │   ├── BidController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   ├── MessageController.php
│   │   │   │   ├── EarningsController.php
│   │   │   │   └── WithdrawalController.php
│   │   │   │
│   │   │   └── Admin/
│   │   │       ├── AuthController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── UserController.php
│   │   │       ├── ProviderVerificationController.php
│   │   │       ├── JobRequestController.php
│   │   │       ├── BookingController.php
│   │   │       ├── PaymentController.php
│   │   │       ├── WithdrawalController.php
│   │   │       ├── DisputeController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── SubcategoryController.php
│   │   │       ├── ReviewController.php
│   │   │       ├── FeaturedController.php
│   │   │       ├── SmsController.php
│   │   │       ├── SettingController.php
│   │   │       ├── ReportController.php
│   │   │       └── LocationController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── IsAdmin.php
│   │   │   ├── IsSeeker.php
│   │   │   ├── IsProvider.php
│   │   │   ├── IsVerifiedProvider.php
│   │   │   └── CheckPhoneVerified.php
│   │   │
│   │   └── Requests/
│   │       ├── Auth/
│   │       │   ├── RegisterRequest.php
│   │       │   ├── LoginRequest.php
│   │       │   └── VerifyOtpRequest.php
│   │       ├── Seeker/
│   │       │   ├── StoreJobRequestRequest.php
│   │       │   ├── UpdateJobRequestRequest.php
│   │       │   └── DirectBookingRequest.php
│   │       ├── Provider/
│   │       │   ├── UpdateProfileRequest.php
│   │       │   ├── StoreBidRequest.php
│   │       │   ├── StoreDirectServiceRequest.php
│   │       │   └── WithdrawalRequest.php
│   │       └── Admin/
│   │           ├── StoreCategoryRequest.php
│   │           └── StoreSettingRequest.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── District.php
│   │   ├── Area.php
│   │   ├── ServiceCategory.php
│   │   ├── ServiceSubcategory.php
│   │   ├── ProviderProfile.php
│   │   ├── ProviderSkill.php
│   │   ├── ProviderVerificationDoc.php
│   │   ├── JobRequest.php
│   │   ├── JobBid.php
│   │   ├── DirectService.php
│   │   ├── Booking.php
│   │   ├── Payment.php
│   │   ├── Message.php
│   │   ├── Review.php
│   │   ├── WithdrawalRequest.php
│   │   ├── Dispute.php
│   │   ├── Notification.php
│   │   ├── FeaturedSubscription.php
│   │   ├── Setting.php
│   │   ├── AdminActivityLog.php
│   │   └── SmsLog.php
│   │
│   ├── Services/
│   │   ├── SmsService.php
│   │   ├── PaymentService.php
│   │   ├── NotificationService.php
│   │   ├── CommissionService.php
│   │   ├── BookingService.php
│   │   ├── OtpService.php
│   │   └── ImageService.php
│   │
│   └── Helpers/
│       └── helpers.php              -- global helper functions
│
├── database/
│   ├── migrations/                  -- all 22 migration files
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── DistrictSeeder.php       -- all 64 BD districts
│       ├── AreaSeeder.php           -- major thanas
│       ├── ServiceCategorySeeder.php -- 20 categories
│       ├── ServiceSubcategorySeeder.php
│       ├── SettingSeeder.php
│       └── AdminUserSeeder.php
│
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   ├── public.blade.php     -- public site layout
│       │   ├── seeker.blade.php     -- seeker dashboard layout
│       │   ├── provider.blade.php   -- provider dashboard layout
│       │   └── admin.blade.php      -- admin panel layout
│       │
│       ├── components/
│       │   ├── public/
│       │   │   ├── navbar.blade.php
│       │   │   ├── footer.blade.php
│       │   │   ├── provider-card.blade.php
│       │   │   ├── service-card.blade.php
│       │   │   └── category-card.blade.php
│       │   ├── shared/
│       │   │   ├── alert.blade.php
│       │   │   ├── badge.blade.php
│       │   │   ├── star-rating.blade.php
│       │   │   ├── avatar.blade.php
│       │   │   ├── breadcrumb.blade.php
│       │   │   ├── empty-state.blade.php
│       │   │   ├── pagination.blade.php
│       │   │   ├── modal.blade.php
│       │   │   └── status-badge.blade.php
│       │   └── admin/
│       │       ├── sidebar.blade.php
│       │       ├── topbar.blade.php
│       │       └── stat-card.blade.php
│       │
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── verify-otp.blade.php
│       │
│       ├── public/
│       │   ├── home.blade.php
│       │   ├── search.blade.php
│       │   ├── category-show.blade.php
│       │   ├── provider-profile.blade.php
│       │   ├── about.blade.php
│       │   └── contact.blade.php
│       │
│       ├── seeker/
│       │   ├── dashboard.blade.php
│       │   ├── profile/
│       │   │   └── edit.blade.php
│       │   ├── job-requests/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── show.blade.php
│       │   ├── bookings/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── messages/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── reviews/
│       │   │   └── create.blade.php
│       │   └── disputes/
│       │       └── create.blade.php
│       │
│       ├── provider/
│       │   ├── dashboard.blade.php
│       │   ├── profile/
│       │   │   ├── setup.blade.php
│       │   │   └── edit.blade.php
│       │   ├── verification/
│       │   │   └── submit.blade.php
│       │   ├── skills/
│       │   │   └── manage.blade.php
│       │   ├── direct-services/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── jobs/
│       │   │   ├── browse.blade.php
│       │   │   └── show.blade.php
│       │   ├── bids/
│       │   │   └── index.blade.php
│       │   ├── bookings/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── messages/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── earnings/
│       │   │   └── index.blade.php
│       │   └── withdrawals/
│       │       ├── index.blade.php
│       │       └── create.blade.php
│       │
│       └── admin/
│           ├── dashboard.blade.php
│           ├── users/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── verifications/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── job-requests/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── bookings/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── payments/
│           │   └── index.blade.php
│           ├── withdrawals/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── disputes/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── categories/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           ├── subcategories/
│           │   ├── index.blade.php
│           │   └── form.blade.php
│           ├── reviews/
│           │   └── index.blade.php
│           ├── sms/
│           │   └── send.blade.php
│           ├── locations/
│           │   └── index.blade.php
│           ├── settings/
│           │   └── index.blade.php
│           └── reports/
│               └── index.blade.php
│
└── routes/
    ├── web.php
    └── (no api.php needed)
```

---

## ═══════════════════════════════════════
## SECTION 6 — ROUTES DEFINITION
## ═══════════════════════════════════════

Define all routes in `routes/web.php` with this structure:

```php
<?php
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────
// PUBLIC ROUTES (No auth required)
// ─────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/services', [ServiceCategoryController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceCategoryController::class, 'show'])->name('services.show');
Route::get('/providers/{user}', [ProviderPublicController::class, 'show'])->name('providers.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');

// AJAX - Location
Route::get('/ajax/areas/{district}', [LocationAjaxController::class, 'byDistrict'])->name('ajax.areas');

// ─────────────────────────────────────────
// AUTH ROUTES (Guests only)
// ─────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'attempt'])->name('login.attempt');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');
});

// ─────────────────────────────────────────
// SHARED AUTH ROUTES
// ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

// ─────────────────────────────────────────
// SEEKER ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'is.seeker'])->prefix('seeker')->name('seeker.')->group(function () {
    Route::get('/dashboard', [Seeker\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/edit', [Seeker\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Seeker\ProfileController::class, 'update'])->name('profile.update');

    // Job Requests
    Route::resource('job-requests', Seeker\JobRequestController::class);
    Route::post('/job-requests/{jobRequest}/cancel', [Seeker\JobRequestController::class, 'cancel'])->name('job-requests.cancel');

    // Bid Management (for seeker to manage bids on their requests)
    Route::post('/bids/{bid}/accept', [Seeker\BidManageController::class, 'accept'])->name('bids.accept');
    Route::post('/bids/{bid}/reject', [Seeker\BidManageController::class, 'reject'])->name('bids.reject');

    // Direct Bookings (book a provider's direct service)
    Route::get('/services/{directService}/book', [Seeker\DirectBookingController::class, 'create'])->name('direct-booking.create');
    Route::post('/services/{directService}/book', [Seeker\DirectBookingController::class, 'store'])->name('direct-booking.store');

    // Bookings
    Route::get('/bookings', [Seeker\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Seeker\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/complete', [Seeker\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [Seeker\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payments
    Route::post('/payments/{booking}/initiate', [Seeker\PaymentController::class, 'initiate'])->name('payments.initiate');
    Route::get('/payments/success', [Seeker\PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/fail', [Seeker\PaymentController::class, 'fail'])->name('payments.fail');
    Route::get('/payments/cancel', [Seeker\PaymentController::class, 'cancel'])->name('payments.cancel');
    Route::post('/payments/ipn', [Seeker\PaymentController::class, 'ipn'])->name('payments.ipn')->withoutMiddleware(['auth']);

    // Messages
    Route::get('/messages', [Seeker\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{booking}', [Seeker\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{booking}', [Seeker\MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/{booking}/poll', [Seeker\MessageController::class, 'poll'])->name('messages.poll');

    // Reviews
    Route::get('/bookings/{booking}/review', [Seeker\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [Seeker\ReviewController::class, 'store'])->name('reviews.store');

    // Disputes
    Route::get('/bookings/{booking}/dispute', [Seeker\DisputeController::class, 'create'])->name('disputes.create');
    Route::post('/bookings/{booking}/dispute', [Seeker\DisputeController::class, 'store'])->name('disputes.store');
});

// ─────────────────────────────────────────
// PROVIDER ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'is.provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [Provider\DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile/setup', [Provider\ProfileController::class, 'setup'])->name('profile.setup');
    Route::get('/profile/edit', [Provider\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Provider\ProfileController::class, 'update'])->name('profile.update');

    // Skills
    Route::get('/skills', [Provider\ProfileController::class, 'skills'])->name('skills.manage');
    Route::post('/skills', [Provider\ProfileController::class, 'updateSkills'])->name('skills.update');

    // Verification
    Route::get('/verification', [Provider\VerificationController::class, 'show'])->name('verification.show');
    Route::post('/verification', [Provider\VerificationController::class, 'submit'])->name('verification.submit');

    // Direct Services
    Route::resource('services', Provider\DirectServiceController::class);

    // Browse Job Requests
    Route::get('/jobs', [Provider\JobBrowseController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{jobRequest}', [Provider\JobBrowseController::class, 'show'])->name('jobs.show');

    // Bids
    Route::post('/jobs/{jobRequest}/bid', [Provider\BidController::class, 'store'])->name('bids.store');
    Route::put('/bids/{bid}', [Provider\BidController::class, 'update'])->name('bids.update');
    Route::delete('/bids/{bid}', [Provider\BidController::class, 'destroy'])->name('bids.destroy');
    Route::get('/bids', [Provider\BidController::class, 'index'])->name('bids.index');

    // Bookings
    Route::get('/bookings', [Provider\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Provider\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [Provider\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/start', [Provider\BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking}/complete', [Provider\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [Provider\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Messages
    Route::get('/messages', [Provider\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{booking}', [Provider\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{booking}', [Provider\MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/{booking}/poll', [Provider\MessageController::class, 'poll'])->name('messages.poll');

    // Earnings & Withdrawals
    Route::get('/earnings', [Provider\EarningsController::class, 'index'])->name('earnings.index');
    Route::get('/withdrawals', [Provider\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [Provider\WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [Provider\WithdrawalController::class, 'store'])->name('withdrawals.store');
});

// ─────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin auth (separate from main auth)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware(['auth', 'is.admin'])->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/suspend', [Admin\UserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/activate', [Admin\UserController::class, 'activate'])->name('users.activate');

        // Provider Verifications
        Route::get('/verifications', [Admin\ProviderVerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{user}', [Admin\ProviderVerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{user}/approve', [Admin\ProviderVerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{user}/reject', [Admin\ProviderVerificationController::class, 'reject'])->name('verifications.reject');

        // Job Requests
        Route::get('/job-requests', [Admin\JobRequestController::class, 'index'])->name('job-requests.index');
        Route::get('/job-requests/{jobRequest}', [Admin\JobRequestController::class, 'show'])->name('job-requests.show');

        // Bookings
        Route::get('/bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');

        // Payments
        Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');

        // Withdrawals
        Route::get('/withdrawals', [Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}', [Admin\WithdrawalController::class, 'show'])->name('withdrawals.show');
        Route::post('/withdrawals/{withdrawal}/approve', [Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

        // Disputes
        Route::get('/disputes', [Admin\DisputeController::class, 'index'])->name('disputes.index');
        Route::get('/disputes/{dispute}', [Admin\DisputeController::class, 'show'])->name('disputes.show');
        Route::post('/disputes/{dispute}/resolve', [Admin\DisputeController::class, 'resolve'])->name('disputes.resolve');

        // Categories
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('categories.subcategories', Admin\SubcategoryController::class)->shallow();

        // Reviews
        Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/hide', [Admin\ReviewController::class, 'hide'])->name('reviews.hide');
        Route::post('/reviews/{review}/show', [Admin\ReviewController::class, 'show'])->name('reviews.show');

        // Featured
        Route::get('/featured', [Admin\FeaturedController::class, 'index'])->name('featured.index');
        Route::post('/featured/{provider}/grant', [Admin\FeaturedController::class, 'grant'])->name('featured.grant');

        // SMS
        Route::get('/sms/send', [Admin\SmsController::class, 'show'])->name('sms.show');
        Route::post('/sms/send', [Admin\SmsController::class, 'send'])->name('sms.send');
        Route::get('/sms/logs', [Admin\SmsController::class, 'logs'])->name('sms.logs');

        // Locations
        Route::get('/locations', [Admin\LocationController::class, 'index'])->name('locations.index');
        Route::resource('locations/districts', Admin\DistrictController::class)->names('districts');

        // Settings
        Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        // Reports
        Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [Admin\ReportController::class, 'export'])->name('reports.export');
    });
});
```

---

## ═══════════════════════════════════════
## SECTION 7 — DESIGN SYSTEM
## ═══════════════════════════════════════

### Color Palette

```css
/* Primary Brand — Deep Forest Green */
--color-primary-50:  #ECFDF5;
--color-primary-100: #D1FAE5;
--color-primary-200: #A7F3D0;
--color-primary-400: #34D399;
--color-primary-600: #059669;
--color-primary-700: #047857;
--color-primary-800: #065F46;
--color-primary-900: #0B4F3C;   /* Main brand color */

/* Accent — Warm Amber/Gold */
--color-accent-400: #FBBF24;
--color-accent-500: #F59E0B;    /* Main accent */
--color-accent-600: #D97706;

/* Neutral */
--color-gray-50:  #F9FAFB;
--color-gray-100: #F3F4F6;
--color-gray-200: #E5E7EB;
--color-gray-400: #9CA3AF;
--color-gray-600: #4B5563;
--color-gray-700: #374151;
--color-gray-900: #111827;

/* Status */
--color-success: #10B981;
--color-danger:  #EF4444;
--color-warning: #F59E0B;
--color-info:    #3B82F6;
```

### Tailwind Config (`tailwind.config.js`)
```js
module.exports = {
    content: ['./resources/**/*.blade.php', './resources/**/*.js'],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    400: '#34D399',
                    600: '#059669',
                    700: '#047857',
                    800: '#065F46',
                    900: '#0B4F3C',
                    DEFAULT: '#0B4F3C',
                },
                accent: {
                    400: '#FBBF24',
                    DEFAULT: '#F59E0B',
                    600: '#D97706',
                },
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            boxShadow: {
                'card': '0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04)',
                'card-hover': '0 4px 12px rgba(0,0,0,0.08), 0 12px 32px rgba(0,0,0,0.06)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
```

### Typography
- **Font:** Inter (load from Google Fonts in layout head)
- **Headings:** `font-weight: 700`, tracked slightly tight
- **Body:** `font-weight: 400`, `line-height: 1.7`
- **Labels/Captions:** `font-weight: 500`, small size, uppercase letter-spacing

### Component Patterns

**Primary Button:**
```html
<button class="inline-flex items-center gap-2 px-6 py-3 bg-primary-900 text-white
               font-semibold rounded-xl hover:bg-primary-800 active:scale-95
               transition-all duration-200 shadow-sm hover:shadow-md">
    Button Text
</button>
```

**Accent Button:**
```html
<button class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white
               font-semibold rounded-xl hover:bg-accent-600 active:scale-95
               transition-all duration-200">
    Button Text
</button>
```

**Card:**
```html
<div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover
            transition-all duration-300 p-6">
    Content
</div>
```

**Provider Card (signature element — used on homepage & search):**
```html
<div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover hover:-translate-y-1
            transition-all duration-300 overflow-hidden group">
    <!-- Color accent bar at top -->
    <div class="h-1.5 bg-gradient-to-r from-primary-600 to-accent-400"></div>
    <div class="p-5">
        <!-- Avatar + Name row -->
        <div class="flex items-start gap-3 mb-3">
            <div class="relative flex-shrink-0">
                <img class="w-14 h-14 rounded-xl object-cover" src="..." alt="...">
                <!-- Verified badge overlay -->
                <span class="absolute -bottom-1 -right-1 bg-accent rounded-full p-0.5">
                    <svg class="w-3 h-3 text-white"><!-- check icon --></svg>
                </span>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 group-hover:text-primary-900 transition-colors">Name</h3>
                <p class="text-sm text-gray-500">Plumber · Jashore</p>
            </div>
        </div>
        <!-- Rating + Jobs -->
        <div class="flex items-center gap-4 text-sm mb-3">
            <span class="flex items-center gap-1 text-accent font-medium">
                ★ 4.8 <span class="text-gray-400">(42)</span>
            </span>
            <span class="text-gray-400">·</span>
            <span class="text-gray-600">120 jobs</span>
        </div>
        <!-- Skills tags -->
        <div class="flex flex-wrap gap-1.5 mb-4">
            <span class="px-2.5 py-1 bg-primary-50 text-primary-800 text-xs font-medium rounded-lg">Pipe fitting</span>
        </div>
        <!-- CTA -->
        <a href="#" class="block text-center py-2.5 bg-primary-900 text-white text-sm
                           font-semibold rounded-xl hover:bg-primary-800 transition-colors">
            View Profile
        </a>
    </div>
</div>
```

### Public Homepage Structure
The homepage (`home.blade.php`) must have these sections in this order:

1. **Hero Section** — Full-width with the headline "আপনার এলাকায়, আপনার কাজ" (Your area, your work). Large search bar (service category + district) overlay on a clean illustration/gradient background. No stock photo — use CSS/SVG abstract shapes in brand colors.

2. **Category Grid** — 5 columns on desktop, 2 on mobile. Icon + name. 20 service categories.

3. **How It Works** — 3 steps for Seekers, 3 steps for Providers. Two-tab switcher using Alpine.js.

4. **Featured Providers** — Horizontal scroll card row of featured/verified providers.

5. **Stats Bar** — Animated counters: Total Providers / Cities Covered / Jobs Completed / Customer Rating.

6. **Trust Section** — 4 trust signals: Verified Providers, Secure Payment, Money-back Guarantee, 24/7 Support.

7. **CTA Banner** — Two CTAs: "Post a Job" (for seekers) and "Join as Provider" (for providers).

8. **Footer** — Logo + tagline, links, social icons, copyright.

### Dashboard UI Patterns

**Seeker Dashboard:**
- Welcome bar with name + quick actions (Post Job, Browse Services)
- Stats row: Active Requests / Total Bookings / Completed / Pending Review
- Recent Activity feed (last 5 job requests + bookings)
- Unread messages widget

**Provider Dashboard:**
- Earnings summary card (total earned / this month / pending release)
- Quick stats: Active Bids / Upcoming Bookings / Pending Reviews / Profile Completeness (%)
- Recent job requests in their skill areas (live feed)
- Performance chart (jobs per month — simple bar chart using Alpine + inline SVG or CSS)

**Admin Dashboard:**
- 8 stat cards in 4x2 grid: Total Users / New Today / Providers Pending Verification / Active Bookings / Revenue This Month / Pending Withdrawals / Open Disputes / Avg Rating
- Recent activity table
- Quick action buttons

### Animations

Add these custom CSS animations to `resources/css/app.css`:
```css
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes counterUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}
/* Stagger children */
.stagger-children > *:nth-child(1) { animation-delay: 0.1s; }
.stagger-children > *:nth-child(2) { animation-delay: 0.2s; }
.stagger-children > *:nth-child(3) { animation-delay: 0.3s; }
.stagger-children > *:nth-child(4) { animation-delay: 0.4s; }
```

---

## ═══════════════════════════════════════
## SECTION 8 — FEATURE SPECIFICATIONS
## ═══════════════════════════════════════

### 8.1 Authentication System

**Registration Flow:**
1. User visits `/register` → chooses role (Seeker / Provider) via radio/toggle
2. Fills: Name, Phone (BD format: 01XXXXXXXXX), Password (min 8), District, Area
3. Submit → OTP sent via BulkSMSBD to phone → redirect to `/verify-otp`
4. OTP valid 5 minutes, max 3 resend attempts per 30 min
5. After OTP verified → redirect based on role:
   - Seeker → `seeker.dashboard`
   - Provider → `provider.profile.setup` (profile setup wizard)

**Login:** Phone + Password. If OTP not verified → resend OTP flow.

**`SmsService.php` — BulkSMSBD integration:**
```php
class SmsService {
    public function send(string $phone, string $message): bool {
        // POST to BulkSMSBD API
        // Log to sms_logs table
        // Return success/failure
    }
    public function sendOtp(string $phone): string {
        // Generate 6-digit OTP
        // Save to users table (otp, otp_expires_at = now+5min)
        // Call send()
        // Return the OTP (for testing log only, never expose)
    }
}
```

### 8.2 Service Categories

**20 Categories with icons (use Heroicons outline):**

| # | Name | Icon | Key Subcategories |
|---|---|---|---|
| 1 | Home Cleaning | `sparkles` | Deep cleaning, Daily cleaning, After-party cleaning |
| 2 | Plumbing | `wrench` | Pipe fitting, Drain cleaning, Tank install, Tap repair |
| 3 | Electrical Work | `bolt` | Wiring, Fan/AC install, Switch repair, Generator |
| 4 | AC Servicing | `sun` | AC cleaning, Gas refill, AC repair, AC install |
| 5 | Painting | `paint-brush` | Interior, Exterior, Wood polish, Wall putty |
| 6 | Carpentry | `scissors` | Door repair, Furniture repair, Cabinet install |
| 7 | Home Cooking | `fire` | Daily cooking, Party cooking, Tiffin service |
| 8 | Babysitting | `heart` | Full-time, Part-time, Night duty |
| 9 | Home Tutoring | `academic-cap` | School subjects, English, Math, Science |
| 10 | Beauty & Salon | `sparkles` | Bridal makeup, Facial, Hair, Manicure |
| 11 | Pest Control | `bug-ant` | Cockroach, Rat, Termite, Mosquito spray |
| 12 | Laundry | `cloud` | Wash & fold, Dry cleaning, Ironing |
| 13 | Driver Service | `truck` | Daily driver, Trip driver, Airport pickup |
| 14 | IT & Repair | `computer-desktop` | PC repair, Printer, CCTV install, Network |
| 15 | Photography | `camera` | Wedding, Product, Event, Portrait |
| 16 | Event Help | `calendar` | Decoration, Catering help, Event management |
| 17 | Gardening | `leaf` | Garden setup, Trimming, Plant care, Rooftop garden |
| 18 | Shifting & Moving | `truck` | Furniture shifting, Packing, Loading |
| 19 | Tailoring | `scissors` | Dress stitching, Alteration, Saree blouse |
| 20 | Security Guard | `shield-check` | Day guard, Night guard, Event security |

### 8.3 Job Request Flow (Seeker → Provider)

1. **Seeker posts request:** Fills form with title, category/subcategory, description, location (district/area/address), budget range (optional), preferred date/time, urgency level, optional photos (max 5, max 2MB each)
2. **Request goes live** — visible to all verified providers in that area with matching skills
3. **Providers bid** — each submits bid amount, message, estimated hours. Max 10 bids per request.
4. **Seeker reviews bids** — sees provider profile, rating, past jobs, bid price, message
5. **Seeker accepts one bid** → all other bids auto-rejected → booking created → SMS to both parties
6. **Payment** — seeker pays online (SSLCommerz) OR selects "Pay after service" (cash)
7. **Provider confirms** → starts job → marks complete → seeker confirms complete
8. **Money released** → review exchange → done

**Job Request Status Flow:**
```
open → assigned (bid accepted) → in_progress → completed → [archived]
     → cancelled (by seeker before assignment)
```

### 8.4 Direct Service Booking

Providers create fixed-price service listings. Seekers can directly book without bidding.

1. Provider creates service listing (title, price, price_type, area coverage, description, photos)
2. Service appears on provider's public profile + search results
3. Seeker clicks "Book Now" → selects date/time → adds note → submits
4. Booking created with `status = pending`
5. Provider has 24 hours to confirm or decline
6. If confirmed → payment → service → complete → review

### 8.5 Payment Flow (SSLCommerz)

```php
// PaymentService.php
class PaymentService {
    public function initiate(Booking $booking): string {
        // Build SSLCommerz POST data
        // Return redirect URL
    }
    public function verifyIpn(Request $request): bool {
        // Server-side val_id verification
        // Update payment status
        // Release booking
    }
}
```

**Commission Calculation (`CommissionService.php`):**
```php
class CommissionService {
    public function calculate(float $amount): array {
        $rate = Setting::get('commission_rate', 12) / 100;
        $fee = round($amount * $rate, 2);
        return [
            'service_amount'   => $amount,
            'platform_fee'     => $fee,
            'provider_earning' => $amount - $fee,
        ];
    }
}
```

### 8.6 Chat / Messaging System

Messages are stored in DB. Frontend polls every 5 seconds using Alpine.js + fetch.

```js
// In message show view - Alpine.js component
Alpine.data('chat', (bookingId, pollUrl) => ({
    messages: [],
    newMessage: '',
    polling: null,
    init() {
        this.poll();
        this.polling = setInterval(() => this.poll(), 5000);
    },
    async poll() {
        const res = await fetch(pollUrl);
        const data = await res.json();
        this.messages = data.messages;
        this.$nextTick(() => this.scrollToBottom());
    },
    async send() {
        if (!this.newMessage.trim()) return;
        await fetch(`/seeker/messages/${bookingId}`, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json'},
            body: JSON.stringify({message: this.newMessage})
        });
        this.newMessage = '';
        this.poll();
    },
    scrollToBottom() {
        const el = this.$refs.messages;
        if (el) el.scrollTop = el.scrollHeight;
    }
}));
```

Poll endpoint returns JSON: `{messages: [{id, sender_id, message, created_at, is_mine}]}`

### 8.7 Provider Earnings & Withdrawal

**Earnings Calculation:**
```php
// Provider earnings dashboard data
$earnings = [
    'total_earned'    => Booking::completed()->forProvider($id)->sum('provider_earning'),
    'this_month'      => Booking::completed()->forProvider($id)->thisMonth()->sum('provider_earning'),
    'pending_release' => Booking::inProgress()->forProvider($id)->sum('provider_earning'),
    'withdrawn'       => WithdrawalRequest::approved()->forProvider($id)->sum('amount'),
    'available'       => $total_earned - $withdrawn,
];
```

**Withdrawal Request:**
- Provider requests withdrawal (min 200 BDT)
- Available balance = total earned (from completed bookings) - already withdrawn
- Admin reviews → clicks "Mark as Paid" → sends BDT manually via bKash/bank → adds note
- Status: `pending → processing → approved`

### 8.8 Provider Verification

New providers see a verification prompt on dashboard. Steps:
1. Upload NID front + back
2. Upload selfie holding NID
3. Submit → status = `pending`
4. Admin reviews docs in admin panel → Approve or Reject with note
5. Approved → `ProviderProfile.is_verified = true`, `verified_at = now()`
6. SMS sent to provider with result
7. Verified badge appears on profile

### 8.9 Notification System

**NotificationService.php:**
```php
class NotificationService {
    public function send(User $user, string $title, string $message, string $type, ?string $url = null, array $data = []): void {
        Notification::create([
            'user_id'    => $user->id,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'data'       => $data,
            'action_url' => $url,
        ]);
        // Optionally also SMS for critical notifications
    }
}
```

**Notification bell in nav:** Alpine.js component fetches unread count every 30s. Click → dropdown shows last 5, link to full list.

### 8.10 Admin Panel Pages

**Dashboard:** Real-time stats, recent registrations, pending verifications count, pending withdrawals sum.

**User Management:** Searchable/filterable table. Filter by role/status. Click user → full profile, booking history, payment history, SMS log.

**Provider Verification Queue:** Table of providers with `pending` verification. Click → view uploaded docs (images in modal). Approve/Reject buttons with note.

**Bookings:** Full booking list with filters (status, date range, amount range). Click → booking detail with all related data.

**Withdrawals:** Table of pending withdrawals. Admin clicks "Mark as Paid" → fills in transaction note → status updated → SMS sent to provider.

**Disputes:** Open disputes. Admin views both sides, can resolve in favor of seeker or provider, or split.

**Settings page:** Grouped settings form. Groups: General, Commission, Featured, SMS, Payment Gateway, Contact. All from `settings` table.

**Reports page:** Date-range picker → generates summary: Revenue, Bookings, New Users, Top Categories, Top Providers. Export to CSV button.

---

## ═══════════════════════════════════════
## SECTION 9 — DEVELOPMENT SEQUENCE
## ═══════════════════════════════════════

Build in this exact order. Complete each phase before starting the next.

### Phase 1 — Foundation (Do First)
1. Fresh Laravel 11 install
2. Configure `.env` (DB, mail, app URL)
3. Install packages (composer + npm)
4. Configure Tailwind, Alpine, Vite
5. Add Inter font to layout head
6. Create all 22 migrations (Section 4)
7. Create all models with relationships
8. Create all seeders, run seeders (districts, areas, categories, settings, admin user)
9. Create route middlewares (IsAdmin, IsSeeker, IsProvider, IsVerifiedProvider)
10. Register middleware in `bootstrap/app.php`

### Phase 2 — Auth System
1. `RegisterController` + `register.blade.php`
2. `OtpController` + `OtpService` + `SmsService` (BulkSMSBD)
3. `LoginController` + `login.blade.php`
4. `LogoutController`
5. `verify-otp.blade.php`
6. Test full registration → OTP → login → logout flow

### Phase 3 — Public Layouts & Homepage
1. `layouts/public.blade.php` (header nav, footer, flash alerts)
2. `components/public/navbar.blade.php` (responsive, hamburger menu)
3. `components/public/footer.blade.php`
4. `HomeController` + `home.blade.php` (all sections from Section 7)
5. `ServiceCategoryController` + category index + show pages
6. AJAX area endpoint (`LocationAjaxController`)

### Phase 4 — Provider Panel
1. `layouts/provider.blade.php` (sidebar, topbar)
2. Profile setup wizard (`profile/setup.blade.php`)
3. Profile edit page
4. Skills management
5. Verification submission
6. Direct service CRUD
7. Job browse + show
8. Bid management
9. Bookings list + show + status updates
10. Messages (with Alpine polling)
11. Earnings dashboard
12. Withdrawals

### Phase 5 — Seeker Panel
1. `layouts/seeker.blade.php`
2. Dashboard
3. Job Request CRUD (with photo upload)
4. Bid review + accept/reject
5. Direct booking flow
6. Bookings + status management
7. Payment flow (SSLCommerz integration)
8. Messages
9. Review creation
10. Dispute filing

### Phase 6 — Admin Panel
1. `layouts/admin.blade.php` (dark sidebar, topbar)
2. Admin auth (separate login)
3. Dashboard with stat cards
4. User management
5. Provider verification queue
6. Job requests viewer
7. Bookings manager
8. Payments ledger
9. Withdrawal management
10. Dispute resolution
11. Category/subcategory CRUD
12. Review moderation
13. SMS blast
14. Settings
15. Reports

### Phase 7 — Search & Provider Profile
1. `SearchController` with filters (category, district, area, min_rating, price_range, verified_only, sort)
2. `search.blade.php` with filter sidebar + results grid
3. `ProviderPublicController` + `provider-profile.blade.php`
4. Public profile: bio, skills, portfolio, direct services, reviews

### Phase 8 — Polish & Production
1. Add `NotificationService` calls to all key actions
2. Notification bell component in layouts
3. All SMS notifications (booking confirmed, bid accepted, withdrawal processed, etc.)
4. `ImageService` — compress/resize uploaded avatars and photos
5. SEO meta tags in all public pages
6. Responsive QA (mobile, tablet, desktop)
7. Error pages (404, 500) with brand design
8. Admin activity logging on critical actions
9. `php artisan storage:link`
10. Production `.env` checklist

---

## ═══════════════════════════════════════
## SECTION 10 — SERVICE CLASSES
## ═══════════════════════════════════════

### `SmsService.php`
```php
// BulkSMSBD integration
// Endpoint: http://bulksmsbd.net/api/smsapi
// Method: POST
// Params: api_key, type=text, number={phone}, senderid=8809617611169, message={text}
// Store api_key from settings table: Setting::get('bulksms_api_key')
// Log every SMS in sms_logs table
```

### `PaymentService.php`
```php
// SSLCommerz integration
// Sandbox URL: https://sandbox.sslcommerz.com/gwprocess/v4/api.php
// Live URL: https://securepay.sslcommerz.com/gwprocess/v4/api.php
// Required fields: store_id, store_passwd, total_amount, currency=BDT,
//                  tran_id, success_url, fail_url, cancel_url,
//                  cus_name, cus_email, cus_phone, cus_add1, cus_city
// IPN validation: POST val_id to https://securepay.sslcommerz.com/validator/api/validationserverAPI.php
```

### `ImageService.php`
```php
// Uses Intervention Image v3
// Resize avatars to max 400x400
// Resize portfolio/job photos to max 1200x900
// Compress quality to 80%
// Store via Storage::put('public/...', ...)
```

### `BookingService.php`
```php
// createFromBid(JobRequest $request, JobBid $bid): Booking
// createFromDirect(DirectService $service, User $seeker, array $data): Booking
// generateRef(): string  -- "LE-2024-00001" format
// releaseEarnings(Booking $booking): void  -- after completion
```

### `helpers.php`
```php
// Money formatting: format_taka(1500) → "৳1,500"
// Time ago in Bangla: time_ago_bn($date)
// Rating stars HTML: rating_stars($rating)
// Phone formatting: format_bd_phone("01711...")
// Truncate text: truncate($text, $length)
```

---

## ═══════════════════════════════════════
## SECTION 11 — IMPORTANT NOTES
## ═══════════════════════════════════════

### ENV Variables Required
```env
APP_NAME="LocalEmployments"
APP_URL=http://localemployments.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=localemployments
DB_USERNAME=root
DB_PASSWORD=

BULKSMS_API_KEY=
BULKSMS_SENDER_ID=8809617611169

SSLCOMMERZ_STORE_ID=
SSLCOMMERZ_STORE_PASS=
SSLCOMMERZ_SANDBOX=true
```

### Seeder: Default Admin
```php
// Email: admin@localemployments.com
// Phone: 01700000000
// Password: Admin@12345 (bcrypt hashed)
// Role: admin
```

### Model Relationships Summary

**User:**
- hasOne ProviderProfile (for providers)
- hasMany JobRequests (as seeker)
- hasMany JobBids (as provider)
- hasMany Bookings (as seeker or provider)
- hasMany Messages
- hasMany Notifications
- belongsTo District, Area

**Booking:**
- belongsTo Seeker (User), Provider (User)
- belongsTo JobRequest (nullable), JobBid (nullable), DirectService (nullable)
- hasOne Payment
- hasMany Messages
- hasOne Review
- hasOne Dispute

### Authorization (Policies or inline checks)
- Seeker can only manage their own job requests
- Seeker can only view their own bookings
- Provider can only manage their own bids, services, bookings
- Users can only read messages in their own bookings
- Admin can access everything under `/admin`
- Use `$this->authorize()` in controllers or inline `abort_if()`

### cPanel Deployment Notes
- Run `composer install --no-dev --optimize-autoloader`
- Run `npm run build`
- Run `php artisan storage:link`
- Run `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Point document root to `/public` folder
- Set `APP_ENV=production`, `APP_DEBUG=false`

### Git Structure
Initialize with `.gitignore` that excludes:
```
/vendor
/node_modules
.env
/public/storage
/storage/*.key
```

---

## ═══════════════════════════════════════
## SECTION 12 — STARTING COMMAND
## ═══════════════════════════════════════

When you (Antigravity) receive this prompt, do the following:

1. Acknowledge you've read the full document
2. State the 5 key architectural decisions you understood:
   - Booking model (both bid + direct)
   - Chat (DB polling via Alpine)
   - Location (district + thana dropdown)
   - Payout (manual admin approval)
   - Mobile (responsive web only)
3. List the first 10 files you will create (Phase 1 of Section 9)
4. Ask if you should begin, then start with Phase 1

Do NOT start writing any code until you've done steps 1-4 above.
Do NOT use Filament, Nova, or any admin panel package.
Do NOT add any package not listed in Section 3.

---

*End of LOCALEMPLOYMENTS_PROJECT_PROMPT.md*
*Generated for Antigravity IDE — Full production-ready specification*
*Domain: localemployments.com | Stack: Laravel 11 + Blade + Tailwind + Alpine.js + MySQL*
