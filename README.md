# School Information System (SIS)

A modern web-based **School Information System (SIS)** designed to streamline school administration and data management. The system provides centralized management of classes, students, teachers, and school information through a responsive dashboard and secure authentication mechanism.

---

## Tech Stack

### Frontend

* Vue 3
* Vuetify
* Vue Router
* Pinia
* Axios

### Backend

* Laravel 13
* RESTful API
* JWT Authentication

### Database

* MySQL

---

## Features

### Authentication & Security

* JWT-based Authentication
* Protected API Routes
* Role-Based Access Control (Admin)

### Dashboard

* School Overview Dashboard
* Statistical Summary Cards
* Quick Access Navigation

### Class Management

* Create, Update, Delete Classes
* Assign Homeroom Teachers
* Class Summary Information

### Student Management

* Add New Students
* Edit Student Information
* Student Data Listing
* Student Search and Filtering

### Teacher Management

* Add New Teachers
* Edit Teacher Information
* Teacher Data Listing

### User Experience

* Responsive Design
* Dark Mode Support
* Modern User Interface
* Single Page Application (SPA)

---

## System Architecture

```text
┌─────────────┐
│   Vue 3     │
│  Frontend   │
└──────┬──────┘
       │
       │ REST API (JSON)
       ▼
┌─────────────┐
│ Laravel API │
│  Backend    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   MySQL     │
│  Database   │
└─────────────┘
```

---

## Authentication Flow

```text
User Login
     │
     ▼
Laravel API
     │
     ▼
Generate JWT Token
     │
     ▼
Store Token (Pinia)
     │
     ▼
Access Protected Routes
```

---

## Project Structure

### Frontend

```text
src/
├── components/
├── layouts/
├── pages/
├── router/
├── services/
├── stores/
├── assets/
└── App.vue
```

### Backend

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Services/
└── Providers/

routes/
├── api.php
```

---

## Installation

### Clone Repository

```bash
git clone https://github.com/BachriBarelang/sis-project.git
cd sis-project
```

---

### Backend Setup

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

---

### Frontend Setup

```bash
cd frontend

npm install

npm run dev
```

---

## API Communication

The frontend communicates with the backend using RESTful APIs and JSON responses.

Example:

```http
POST /api/login

GET /api/classes

GET /api/students

GET /api/teachers
```

---

## Service-Oriented Architecture (SOA) Principles

This project follows several Service-Oriented Architecture (SOA) principles:

* Service Provider (Laravel REST API)
* Service Consumer (Vue 3 Frontend)
* Loose Coupling between frontend and backend
* Standardized Service Contracts (JSON)
* Service Reusability
* Service Abstraction
* Service Autonomy
* JWT-based Security
* Interoperability with external systems

---

## Current Status

| Module                | Status         |
| --------------------- | -------------- |
| Authentication        | ✅ Completed    |
| Dashboard             | ✅ Completed    |
| Class Management      | ✅ Completed    |
| Student Management    | ✅ Completed    |
| Teacher Management    | ✅ Completed    |
| API Integration       | ✅ Completed    |
| JWT Authentication    | ✅ Completed    |
| Responsive UI         | ✅ Completed    |
| Production Deployment | 🔄 In Progress |

---

## Future Enhancements

* Teacher Role
* Homeroom Teacher Role
* Student Role
* Attendance Management
* Academic Grading System
* Schedule Management
* Notification System
* Reporting & Analytics
* Parent Portal

---

##License

This project is developed for educational and institutional purposes. Feel free to modify and extend it according to your organization's requirements.
