# Rahhal (رحّال) - Travel Booking Website

A full-stack travel website where a traveler books **flights, hotels, hosted homes (to live the country's customs and traditions), restaurants and tourist attractions** in one place.

Built with **PHP + MySQL** (XAMPP) and **HTML + CSS + JavaScript + Bootstrap**.

---

## خطوات التشغيل (Setup on XAMPP)

1. ثبّتي [XAMPP](https://www.apachefriends.org/) وشغّلي **Apache** و **MySQL** من الـ Control Panel.
2. انسخي فولدر `rahhal` جوه:
   - Windows: `C:\xampp\htdocs\`
   - Mac/Linux: `/opt/lampp/htdocs/`
3. افتحي المتصفح على `http://localhost/phpmyadmin` → تاب **Import** → اختاري ملف `install.sql` → **Go**.
   - ده بيخلق قاعدة بيانات اسمها `rahhal` وكل الجداول والداتا التجريبية.
4. افتحي الموقع على `http://localhost/rahhal`

> لو الـ MySQL عندك ليه باسورد، غيّري `DB_USER` و `DB_PASS` في أول ملف `config.php`.

## حسابات جاهزة (Demo accounts)

| Role  | Email             | Password  |
|-------|-------------------|-----------|
| Admin | admin@rahhal.com  | admin123  |
| User  | demo@rahhal.com   | demo123   |

الأدمن بيشوف لينك **Admin** في الـ navbar بعد تسجيل الدخول.

## Features

- Search + price filter لكل خدمة، وبحث عام من الصفحة الرئيسية
- Booking flow كامل: اختيار → تفاصيل → تأكيد → Confirmation code (مثال: `RH-3F9A2C1B`)
- Register / Login بجلسات (Sessions) وتشفير الباسورد بـ `password_hash`
- My Bookings: المستخدم يشوف حجوزاته ويقدر يلغيها
- Admin panel: إحصائيات + CRUD كامل (إضافة/تعديل/حذف) لكل الخدمات + عرض كل الحجوزات
- Hosted Homes: كل بيت معاه وصف التجربة الثقافية اللي المضيف بيقدمها

## Project structure

```
rahhal/
├── config.php          # Database settings (edit DB_USER / DB_PASS here)
├── install.sql         # Creates the database, tables and demo data
├── index.php           # Home page (hero search + categories + featured homes)
├── search.php          # Global search across all services
├── flights.php ...     # One thin page per service (uses includes/listing_page.php)
├── details.php         # Details page for any item (?type= &id=)
├── book.php            # Booking form (login required) + saves the booking
├── confirmation.php    # Shows the confirmation code after booking
├── login.php / register.php / logout.php
├── my_bookings.php     # The user's bookings + cancel
├── cancel_booking.php
├── includes/
│   ├── init.php        # Loads everything (each page starts with it)
│   ├── db.php          # PDO connection
│   ├── functions.php   # Type registry, queries, card rendering, helpers
│   ├── auth.php        # Session, current_user, require_login, require_admin
│   ├── header.php      # Navbar + page head
│   ├── footer.php
│   └── listing_page.php# Shared listing template (search + filters + cards)
├── admin/
│   ├── index.php       # Dashboard with counts
│   ├── items.php       # List items of a type
│   ├── item_form.php   # Add / edit form (works for all types)
│   ├── delete.php
│   └── bookings.php    # All bookings with user names
└── assets/
    ├── css/style.css
    └── js/main.js      # Live total on the booking form + delete confirmation
```

## شكل قاعدة البيانات

- `users` (id, name, email, password, is_admin, created_at)
- `flights` (id, from_city, to_city, flight_date, airline, price, seats)
- `hotels` (id, city, name, price_per_night, rating, description)
- `homes` (id, city, country, host_name, title, experience_description, price_per_night)
- `restaurants` (id, city, name, cuisine, avg_price, description)
- `attractions` (id, city, name, ticket_price, description)
- `bookings` (id, user_id, item_type, item_id, booking_date, quantity, total_price, confirmation_code, status, created_at)

نقطة حلوة للمناقشة: جدول `bookings` واحد بيخدم الخمس خدمات عن طريق عمود `item_type`.
