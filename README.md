<p align="center">
  <img src="https://service.rcproject.it/statify/images/logo.jpg" alt="Statify Logo" width="200">
</p>

<h1 align="center">Statify ⚡</h1>

<p align="center">
  <strong>Real-time server & service monitoring - The ultimate heartbeat monitor for your infrastructure.</strong><br>
  Keep track of servers, web endpoints, and databases in real-time, built elegantly on top of Laravel.
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"></a>
  <a href="#license"><img src="https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge" alt="License"></a>
</p>

---

## 🚀 Overview

**Statify** is a self-hosted, powerful, and beautifully designed monitoring dashboard. Say goodbye to expensive SaaS monitoring tools. Statify pings your services, checks your SSL certificates, monitors server health (CPU, RAM, Disks), and immediately notifies your team via **Telegram** or **Email** the second something goes wrong.

Whether you are managing a single web app, a cluster of databases, or physical NAS arrays, Statify keeps you in control.

## ✨ Key Features

- 🟢 **Uptime & Endpoint Monitoring:** HTTP/HTTPS checks to ensure your websites and APIs are returning `200 OK`.
- 🔒 **SSL Certificate Tracking:** Get warned days before your SSL certificates expire.
- 🖥️ **Hardware & Server Health:** Monitor Disk Usage, CPU load, and RAM via SSH connections. Perfect for Linux servers, Synology, or QNAP NAS.
- 📡 **Ping & Port Checks:** Raw ICMP pings and TCP port checks for databases (MySQL, PostgreSQL, Redis).
- 🚨 **Multi-Channel Alerts:** Native integrations for instant push notifications via **Telegram Bots** and **Email**.
- ⚡ **Real-Time Dashboard:** Built with Livewire & Alpine.js for a reactive, modern UI (Dark Mode included!).
- صف **Queue Powered:** Relies on Laravel Horizon/Redis to handle thousands of concurrent checks without sweating.

---

## 📸 Screenshots

|                                Welcome Screen                                 |                              Dashboard Overview                               |
| :---------------------------------------------------------------------------: | :---------------------------------------------------------------------------: |
| <img src="https://service.rcproject.it/statify/images/img_1.jpg" width="400"> | <img src="https://service.rcproject.it/statify/images/img_d.jpg" width="400"> |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11, PHP 8.2+
- **Frontend:** Tailwind CSS, Flowbite, Alpine.js, Livewire
- **Monitoring Engines:** Spatie Uptime Monitor & Server Monitor
- **Queues:** Redis & Laravel Horizon

---

## 📦 Installation

To get Statify up and running on your server, follow these simple steps:

### 1. Clone the repository

```bash
git clone https://github.com/cranties/Statify.git
cd statify
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Run Installer

```bash
php artisan statify:install
```

### 5. Configure Cron

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 6. Configure Queue

```bash
* * * * * cd /path/to/your/project && php artisan queue:work --stop-when-empty --tries=3 --timeout=60 >> /dev/null 2>&1
```

### 7. Configure Statify SubFolder

Statify is designed to be installed as a subfolder of your Apache/NGinx; you can then access your Statify server at https://www.yourdomain.com/statify.
If you prefer to change the destination folder or path, remember to edit the .htaccess file in the puclic folder, specifically the "RewriteBase" directive.

Configure your Apache/NGinx server to use the alias for Statify.

Example for Apache:

```bash
	Alias /statify /var/www/html/statify/public
	<Directory /var/www/html/statify/public>
		AllowOverride All
		Require all granted
		Options Indexes FollowSymLinks
	</Directory>
```

### 8. Start using Statify!

Connect with a browser to https://www.yourdomain.com/statify and use the credentials created during installation to start configuring Statify!

## 🤝 Contributing

Contributions make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are greatly appreciated.

Fork the Project

Create your Feature Branch (git checkout -b feature/AmazingFeature)

Commit your Changes (git commit -m 'Add some AmazingFeature')

Push to the Branch (git push origin feature/AmazingFeature)

Open a Pull Request

## 📄 License

Distributed under the MIT License. See LICENSE for more information.




<p align="center">
<i>Built with ❤️ for SysAdmins and Developers.</i>
</p>
