# 📝 Employee Work Management System – Pending Tasks (README)

This project is a dashboard-based time and user management system for employees and admins. The current version includes user registration, authentication, profile editing, timesheet logging, FAQ, and admin control panels.

> This document outlines all **remaining tasks and features** required to complete the system according to the final project description.

---

## ✅ Completed Features

- User login & session storage via localStorage
- Admin/user role system with conditional menu access
- Profile page (My Page) with editable data and image upload
- Timesheet: Admin view of all users (month-based)
- Timesheet: Logged-in user sees their own monthly records
- FAQ with collapsible questions
- User Management (CRUD) for Admin
- Responsive layout and component structure
- SQLite database integration for users and logs

---

## 🔧 Remaining Features (To Do)

### 1. 📄 Leave Application System
**Status:** Not started  
**Required:**
- A new panel or page: `📬 Apply Leave`
- Logged-in users can:
  - Select leave type (vacation, sick, etc.)
  - Set start and end dates
  - Add reason and optional message
- Admin can:
  - View all leave requests
  - Approve or reject with comments
- Database: Create `leave_requests` table

---

### 2. 🕒 Timesheet Enhancements
**Status:** Partially complete  
**Required:**
- View switcher: Weekly / Monthly / Yearly
- Remaining hours counter per week/month
- Color indicators for:
  - Approved entries
  - Modified by admin
- Printable/downloadable version (PDF/Excel – optional)

---

### 3. 📣 Logged-in User Contact Support
**Status:** Not implemented  
**Required:**
- If logged in → FAQ displays a "Direct Contact" section
- Shows:
  - Admin name
  - Email and phone number
- Optional: Contact form with auto-filled user info

---

### 4. 🧾 Documentation & Logs
**Status:** Not available  
**Required:**
- Excel timesheet logs for each developer (GP2 team)
- Word report including:
  - Project plan
  - Screenshots of UI
  - Feature overview
  - Known bugs (if any)

---

### 5. 🛡️ Extra UX/Validation/Polish
**Status:** Varies  
**Suggestions:**
- Form validation (highlight errors visually)
- Display feedback after all actions (edit, submit, login, etc.)
- Handle edge cases for empty fields or failed uploads

---

## 📁 To Structure (Optional Improvements)
- Convert to modular PHP pages (not single-page layout)
- Migrate from localStorage to secure PHP session
- Replace all `alert()` with proper modal or inline feedback
- Add internationalization (English/Norwegian toggle)

---

## 🏁 Suggested Completion Order

1. Implement Leave Application System
2. Add weekly/monthly/yearly filtering in Timesheet
3. Activate “Immediate Contact” in FAQ
4. Fill in documentation & team Excel work logs
5. Polish UX and clean up redundant code

---

## 👥 Contributors

This project is developed by Team GP2 – in collaboration with NKEY AS.

📦 Installation Guide (using XAMPP)
✅ Step-by-step:
Download ZIP or clone the repository:

bash
Kopier
Rediger
git clone https://github.com/ArvidKodehode/Employee-Work-Management-System.git
Move the project folder to your htdocs directory:

Example: C:\xampp\htdocs\Employee-Work-Management-System

Start XAMPP and launch:

✅ Apache

✅ SQLite requires no server setup

Run the app
Open your browser and visit:

pgsql
Kopier
Rediger
http://localhost/Employee-Work-Management-System/index.html
✅ You can now use the system locally!

📦 Инструкция по установке (с использованием XAMPP)
✅ Пошаговая инструкция:
Скачайте ZIP или клонируйте репозиторий:

bash
Kopier
Rediger
git clone https://github.com/ArvidKodehode/Employee-Work-Management-System.git
Переместите папку проекта в каталог htdocs:

Пример: C:\xampp\htdocs\Employee-Work-Management-System

Запустите XAMPP и включите:

✅ Apache

✅ SQLite не требует настройки сервера

Откройте приложение
Введите в браузере:

pgsql
Kopier
Rediger
http://localhost/Employee-Work-Management-System/index.html
✅ Система готова к использованию локально!