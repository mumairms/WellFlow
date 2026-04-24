# 🌸 WellFlow

WellFlow is an AI-powered menstrual health tracking web application designed to help users manage cycle tracking, health reminders, doctor consultations, and personalized wellness insights through a modern full-stack platform.

Built using PHP, MySQL, Bootstrap, JavaScript, PHPMailer, and FPDF, WellFlow combines healthcare support with smart automation and premium UI design.

---

## 🚀 Live Project

wellflow.wuaze.com

---

## ✨ Features

### 🔐 Authentication System

* Secure user registration and login
* Password hashing using PHP `password_hash()`
* Session-based authentication
* Protected dashboard access

### 📅 Smart Cycle Tracking

* Add and manage menstrual cycle records
* Automatic cycle length calculation
* Historical cycle tracking
* Calendar-based cycle visualization

### 🤖 AI-Based Prediction

* Smart next period prediction using cycle history
* Average cycle length analysis
* Personalized health insights

### 📩 Reminder Notifications

* Email-based reminder alerts using PHPMailer
* Upcoming period notifications
* Health alert reminders

### 🏥 Doctor Consultation Booking

* Book doctor appointments directly from dashboard
* Health consultation support system

### 📄 PDF Health Reports

* Generate downloadable PDF reports using FPDF
* Cycle summary reports
* Health history documentation

### 🌙 Premium UI + Dark Mode

* Modern premium dashboard design
* Mobile responsive interface
* Dark mode support
* Professional startup-style design

### 📱 Progressive Web App (PWA)

* Installable mobile-friendly web app
* App icon support using manifest.json
* Service worker integration

### 🧠 Chatbot Assistant

* Health support chatbot assistant
* Basic symptom and guidance interaction

### 🛠 Admin Panel

* Admin dashboard support
* User management
* System monitoring

---

## 🛠 Tech Stack

### Frontend

* HTML5
* CSS3
* Bootstrap 5
* JavaScript

### Backend

* PHP

### Database

* MySQL

### Libraries / Tools

* PHPMailer
* FPDF
* GitHub
* InfinityFree Hosting
* phpMyAdmin

---

## 📁 Project Structure

```text
wellflow/
│
├── assets/
│   ├── css/
│   └── images/
│       └── icon.png
│
├── config/
│   └── db.php
│
├── lib/
│   ├── fpdf/
│   └── PHPMailer/
│
├── modules/
│   ├── cycle/
│   ├── doctor/
│   └── notifications/
│
├── admin.php
├── calendar.php
├── chatbot.php
├── dashboard.php
├── generate_report.php
├── login.php
├── register.php
├── logout.php
├── manifest.json
├── service-worker.js
├── index.php
├── README.md
└── .gitignore
```

---

## ⚙️ Installation Setup

### Step 1 — Clone Repository

```bash
git clone https://github.com/yourusername/WellFlow.git
```

---

### Step 2 — Move Project to XAMPP

Copy the project folder to:

```text
C:\xampp\htdocs\
```

---

### Step 3 — Import Database

1. Open phpMyAdmin
2. Create database:

```sql
wellflow
```

3. Import SQL file

---

### Step 4 — Configure Database

Open:

```php
config/db.php
```

Update:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "wellflow";
```

For InfinityFree hosting:

```php
$host = "sql100.infinityfree.com";
$username = "your_db_username";
$password = "your_vPanel_password";
$database = "your_database_name";
```

---

### Step 5 — Run Project

Open browser:

```text
http://localhost/wellflow/
```

or live deployment:

```text
https://wellflow.wuaze.com/
```

---

## 💼 Resume Project Title

### AI-Powered Menstrual Health Tracking System — WellFlow

---

## 📌 Future Enhancements

* Advanced AI prediction model
* Real doctor API integration
* WhatsApp reminder notifications
* Payment gateway for consultations
* Advanced chatbot intelligence
* Analytics dashboard
* Multi-language support

---

## 👨‍💻 Developed By

### Mohammed Umair

Passionate Full Stack Developer focused on building real-world impactful software solutions with strong UI, smart backend systems, and scalable architecture.

---

## ⭐ Support

If you like this project:

* Star the repository ⭐
* Fork the project 🍴
* Share feedback 🚀

---

## 📜 License

This project is developed for educational, portfolio, and professional showcase purposes.
