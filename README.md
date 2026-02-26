# ExamPortal — Laravel 11 Online Examination System

A full-featured online examination and student management portal built with **Laravel 11 + Breeze**.

## Features

- **Two Roles**: Lecturer and Student
- **Authentication**: Secure login via Laravel Breeze
- **Class Management**: Lecturers create classes and enroll students
- **Subject Management**: Multiple subjects per class
- **Exam Creation**: Multiple-choice (auto-graded) and open-text questions
- **Access Control**: Students only see exams from their enrolled classes
- **Time-Limited Exams**: Live countdown timer with auto-submit
- **Scheduling**: Optional start/end datetime windows for exams
- **Manual Grading**: Lecturers grade open-text answers
- **Results Dashboard**: Score, percentage, pass/fail, answer review

## Tech Stack

- PHP 8.2+, Laravel 11, Laravel Breeze (Blade + Tailwind CSS)
- MySQL / SQLite (configurable)

## Installation
```bash
git clone <your-repo-url> exam-portal
cd exam-portal
cp .env.example .env
composer install
php artisan key:generate
```

Configure your database in `.env`, then:
```bash
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Default Credentials (after seeding)

| Role     | Email                 | Password |
|----------|-----------------------|----------|
| Lecturer | lecturer@example.com  | password |
| Student  | student1@example.com  | password |
| Student  | student2@example.com  | password |

*(students 1–5 are created, all enrolled in CS101)*

## Folder Highlights

| Path | Purpose |
|------|---------|
| `app/Models/` | Eloquent models with relationships |
| `app/Http/Controllers/Lecturer/` | All lecturer CRUD logic |
| `app/Http/Controllers/Student/` | Exam taking and results |
| `app/Http/Middleware/CheckRole.php` | Role-based access guard |
| `resources/views/lecturer/` | Lecturer blade views |
| `resources/views/student/` | Student blade views |
| `routes/web.php` | All named routes grouped by role |





<br><br>





<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
