# 🚀 Shaghlni — Job Board Platform

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![Groq](https://img.shields.io/badge/Groq-Llama_3-f55036?style=for-the-badge)](https://groq.com)
[![Laravel Queues](https://img.shields.io/badge/Laravel_Queues-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/queues)
[![License](https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge)](LICENSE)

**شغلني** — *"Employ Me"* in Arabic

> A full-featured job board platform built with Laravel as a **summer learning project** — practicing the complete software engineering lifecycle from design to deployment.

🌐 **Live Demo:** [Job Seeker App](https://job-app.up.railway.app/) · [Admin Back Office](https://job-backoffice.up.railway.app/login)

</div>

---

## 📖 About

**Shaghlni** connects job seekers with employers through two independent Laravel applications sharing a single database:

| App | Description |
|---|---|
| **`job-app`** | Job Seeker Portal — browse jobs, apply, track applications |
| **`job-backoffice`** | Admin & Company Owner Panel — manage companies, vacancies, and applicants |
| **`job-worker`** | **Job-App-Worker** — Background process handling AI evaluations via Laravel Queues |

This project was built to go **beyond just writing code** — practicing the full engineering process:

> 📄 Requirements (SRS) → 🏗️ System Design → 🔄 Workflow → 🗄️ ERD → 🏛️ Architecture → 🚀 Deployment

---

## 📂 Documentation

| Document | File |
|---|---|
| 🏛️ Project Structure | [📄 View PDF](docs/Project_Structure.pdf) |
| 🗄️ Database ERD | [📄 View PDF](docs/ERD.PDF) |
| 🧑‍💼 Job Seeker Flow | [📄 View PDF](docs/Job_Seeker_Flow.pdf) |
| 🛠️ Employer & Admin Flow | [📄 View PDF](docs/Employer&Admin_Flow.pdf) |
| 🔑 Demo Credentials | [📄 View Below](#-demo-credentials) |

---

## 🔑 Demo Credentials

To test the platform, you can use the following pre-seeded accounts:

| Role | Email | Password | Application |
|---|---|---|---|
| **Admin** | `admin@admin.com` | `12345678` | [Back Office](https://job-backoffice.up.railway.app/login) |
| **Company Owner** | `darian35@example.net` | `12345678` | [Back Office](https://job-backoffice.up.railway.app/login) |
| **Job Seeker** | `idietrich@example.com` | `12345678` | [Job App](https://job-app.up.railway.app/login) |

> [!NOTE]
> These accounts are part of the initial database seed. If you are running locally, you can also find other generated users using `php artisan tinker`.

---

## 🛠️ Tech Stack

**Backend:** Laravel 12.x · PHP 8.4+ · Eloquent ORM · Laravel Breeze  
**Frontend:** Blade · Tailwind CSS 4.x · Alpine.js 3.x · Vite  
**AI/LLM:** Groq API · Llama 3.3 · Background Queues  
**Database:** MariaDB (Docker) · phpMyAdmin (Docker)  
**DevOps:** Git · WSL 2 (Ubuntu) · Docker · Railway (hosting)

---

## ⚙️ Background Processing (Queues)

To ensure high performance, this project uses **Laravel Queues** to offload long-running tasks from the main request cycle:

- **Asynchronous Workflow:** When a user submits an application, the AI scoring task is dispatched to a queue. The user gets an immediate response while the worker processes the feedback.
- **Queue Driver:** Configured to use the `database` driver for reliable task management.
- **Worker Process:** A dedicated **Job-App-Worker** (running `php artisan queue:work`) continuously monitors and executes jobs in the background.

---

## ✨ Key Features

- 🔍 Browse and search job vacancies with filtering by type, location, and category
- 📝 Apply to jobs with resume upload (new or existing)
- 📊 Track application status — `Pending` / `Accepted` / `Rejected`
- 🔒 Role-based access control — `admin` / `company-owner` / `job-seeker`
- 📈 Analytics dashboard with active users, vacancies, and conversion rates
- 🤖 **AI Application Scoring** — Automatic compatibility score (0-10) using Groq API
- 💡 **AI Feedback** — Instant, actionable feedback for candidates to improve their applications
- ⚡ **Background Processing** — AI evaluations handled asynchronously via Laravel Queues
- 🗑️ Soft delete & restore for companies, vacancies, and applications

---

## 🎓 What I Learned

| # | Topic | Applied In |
|---|---|---|
| 1 | **MVC Architecture** | Every feature follows Model → Controller → View |
| 2 | **Blade Template Engine** | Layouts, components, directives (`@auth`, `@can`) |
| 3 | **Eloquent ORM & Models** | Relationships, soft deletes, eager loading |
| 4 | **Controllers** | RESTful resource controllers with full CRUD |
| 5 | **Forms & Validation** | Laravel Form Requests for all user input |
| 6 | **Authentication** | Laravel Breeze (session-based) in both apps |
| 7 | **API Authentication** | Sanctum-ready token-based auth structure |
| 8 | **Authorization** | Role middleware + Laravel Policies (Gates) |
| 9 | **AI Integration** | Background evaluation using Groq API & JSON mode |
| 10 | **Queues & Jobs** | Offloading heavy AI tasks to Database queues |

---

<div align="center">

**Made with ❤️ by [Mohamad Walid](https://github.com/MohmadWalid) — Summer 2025**

</div>
