# 🧾 General Ledger System (GL)

> **A modern, modular accounting system for managing journal entries, transactions, and financial statements.**

---

## 🚀 Overview

**General Ledger (GL)** is a web-based accounting system built to handle financial operations such as **journal posting**, **transaction tracking**, and **ledger reporting**.  
It supports **role-based access**, **dynamic menu rendering**, and **modular integration** with other accounting modules.

---

## 🏗️ Tech Stack

| Layer | Technology |
|-------|-------------|
| Backend | PHP (CodeIgniter 3) |
| Frontend | HTML5, Bootstrap 5, jQuery, SweetAlert2 |
| Database | MySQL / MariaDB |
| Authentication | Session-based (Custom Login) |
| Role Management | Dynamic Role & Menu Access Control |

---

## ⚙️ Features

✅ **Dashboard Overview** – real-time system status and summary  
✅ **Role-Based Access** – show/hide menus dynamically per user role  
✅ **Dynamic Sidebar Menu** – automatically generated from DB  
✅ **Auto Session Expiration** – logout after idle time  
✅ **SweetAlert Notifications** – beautiful alert messages  
✅ **Master Data Management** – Company, Department, Cost Center, etc.  
✅ **Secure Authentication** – password hashing and access validation  
✅ **Modular Controller Loader** – `tocontroller()` for smooth navigation  

---

## 🧩 System Architecture

```plaintext
┌───────────────────────────────────────────────┐
│                    Client                     │
│       HTML + Bootstrap + jQuery + AJAX        │
└────────────────────────────┬──────────────────┘
                             │
                             ▼
┌───────────────────────────────────────────────┐
│                CodeIgniter 3 MVC              │
│  Controllers  →  Models  →  Views             │
│  Auth System  →  Role Access Middleware       │
│  Session Helper  →  SweetAlert FlashData      │
└────────────────────────────┬──────────────────┘
                             │
                             ▼
┌───────────────────────────────────────────────┐
│                   Database                    │
│  Tables: users, roles, menus, role_menu_access │
│  Relation: role_id ↔ menu_id                   │
└───────────────────────────────────────────────┘
