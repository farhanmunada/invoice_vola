# Invoice Vola - Modern Invoicing System

Invoice Vola is a powerful, modern, and user-friendly invoicing system built with Laravel 11. Designed specifically for printing businesses, creative studios, and small enterprises to streamline their billing workflow, track payments, and manage customer relations effortlessly.

![Landing Page Preview](https://github.com/farhanmunada/invoice_vola/raw/main/public/images/readme_preview.png) *(Note: Add your own screenshots to public/images)*

## 🚀 Key Features

### 1. Advanced Dashboard Analytics
- **Real-time Stats**: Track today's income, pending payments, and active orders at a glance.
- **Production Overview**: Visual breakdown of invoice statuses (Paid, Partially Paid, Unpaid).
- **Financial Trends**: Interactive charts showing income trends over time.
- **Quick Actions**: Prominent "Create Invoice" button for rapid workflow.

### 2. Dynamic Invoice Management
- **Smart Item Rows**: Add multiple items (Cetak, Apparel, or Custom services) with dynamic pricing.
- **Real-time Calculations**: Automatic calculation of subtotals, discounts (Nominal/Percent), and Down Payments (DP).
- **Flexible Payments**: Support for partial payments (DP) and full settlement with multiple payment methods (Cash, Transfer, QRIS).
- **History & Search**: Detailed history of all transactions with advanced filtering.

### 3. Integrated Customer Portal
- **Customer CRM**: Manage detailed profiles including contact info, addresses, and internal notes.
- **Instant Creation**: Add new customers directly from the invoice form via a modern modal popup without losing your progress.

### 4. Professional Printing Layouts
- **A5 Optimized**: Custom-tailored print layout designed for standard A5 billing paper.
- **Dynamic Branding**: Automatic inclusion of shop logo, name, and address from system settings.
- **Clean Media Query**: specialized `@media print` CSS ensures zero clutter from browser headers/footers.

### 5. Custom Branding & UI
- **Modern Design**: Premium "Indigo & Slate" theme with a focus on whitespace and rounded elements.
- **Shop Settings**: Configure your shop name, contact details, and upload your custom logo via the admin panel.
- **Glassmorphism Landing**: A beautiful, modern entry point for the system.

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Frontend**: Tailwind CSS, Alpine.js (via Breeze)
- **Icons**: Lucide Icons
- **Database**: MySQL / PostgreSQL
- **Asset Bundling**: Vite

## 📥 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/farhanmunada/invoice_vola.git
   cd invoice_vola
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit `.env` and set your database credentials, then run:
   ```bash
   php artisan migrate --seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Run the Application**
   ```bash
   npm run dev
   php artisan serve
   ```

## 🔐 Credentials
Default admin account (if seeded):
- **Email**: `admin@admin.com`
- **Password**: `password`

---
Developed with ❤️ by **Farhan Munada** & Codebase enhanced by **Antigravity**.
