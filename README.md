# Multi-Vendor POS System 

A web-based Point of Sale (POS) system built using Laravel, supporting multiple vendors. This system allows vendors to manage their sales, products, and inventory from a centralized dashboard. Ideal for small businesses, retail stores, and marketplaces.

---

## 🚀 Features

- Multi-vendor support
- Inventory management
- Sales and transaction tracking
- Vendor registration and authentication
- Product catalog with categories and variants
- User roles and permissions
- Invoice generation and printing
- Responsive admin dashboard
- Reports and analytics

---

## 🛠 Tech Stack

- **Backend:** Laravel 10
- **Database:** MySQL / MariaDB
- **Authentication:** Laravel Breeze
- **API:** RESTful API 

---

## ⚙️ Installation

### Prerequisites

- PHP >= 8.0
- Composer
- Node.js & NPM
- MySQL or MariaDB

### Steps

```bash
# Clone the repository
git clone https://github.com/AhmedDip/multi-vendor-pos.git
cd multi-vendor-pos

# Install dependencies
composer install
npm install && npm run dev

# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure your database in .env then run:
php artisan migrate --seed

# Start the local development server
php artisan serve
```

---
