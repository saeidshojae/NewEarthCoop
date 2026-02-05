# 🌍 NewEarthCoop

[![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js)](https://alpinejs.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**NewEarthCoop** is a next-generation economic and social cooperation platform designed for the decentralized world. It empowers users to form smart cooperatives, manage collaborative projects, and leverage AI-driven assistance to solve real-world problems from local neighborhoods to global scales.

---

## ✨ Key Innovations

### 🤖 Najm Hoda: Your AI Mission Control

Our flagship **AI Assistant** is more than just a chatbot. It's a suite of specialized agents:

- **🔧 Engineer:** Technical support and system architecture.
- **✈️ Pilot:** Project management and operational guidance.
- **👨‍✈️ Steward:** User onboarding and general support.
- **📖 Guide:** Strategic planning and cooperative education.
  _Integrated with OpenAI and a custom Knowledge Base (RAG)._

### 🎨 Unified Modern UI

A refined, seamless user experience built with:

- **Tailwind CSS & Alpine.js:** For ultra-fast, reactive, and mobile-first interfaces.
- **Smart Stacking Contexts:** Persistent floating components that stay visible across navigation.
- **Bi-directional Support:** Full RTL (Persian/Arabic) and LTR (English) localization.

### 👥 Intelligent Grouping System

- **Geo-Filtering:** Automatic organization from neighborhood (Local) to planetary (Global) levels.
- **Skill-Based Matching:** Connects users based on profession, expertise, and shared goals.

---

## 🛠️ Technology Stack

| Layer         | Technology                                  |
| :------------ | :------------------------------------------ |
| **Backend**   | Laravel 9.x, PHP 8.1+, MySQL 8.0            |
| **Frontend**  | Tailwind CSS 3, Alpine.js, Blade Components |
| **AI Layer**  | OpenAI API, Custom RAG, NajmHoda Service    |
| **Real-time** | Pusher, Laravel Echo                        |
| **Auth**      | Laravel Sanctum, Multi-step Registration    |

---

## 🚀 Getting Started

### Prerequisites

- **PHP** 8.1 or higher
- **Composer** 2.x
- **Node.js** 16.x & **NPM**
- **MySQL** 8.0

### Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/MoDarK-MK/NewEarthCoop.git
   cd NewEarthCoop
   ```

2. **Install dependencies:**

   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration:**
   Set your `DB_*` credentials in `.env`, then run:

   ```bash
   php artisan migrate --seed
   ```

5. **AI Activation:**
   Add your OpenAI key to enable Najm Hoda:

   ```env
   OPENAI_API_KEY=sk-xxxx...
   NAJM_HODA_ENABLED=true
   ```

6. **Launch:**
   ```bash
   npm run dev & php artisan serve
   ```

---

## 🏗️ Project Architecture

```text
NewEarthCoop/
├── app/Modules/        # Domain-driven modules (Blog, NajmBahar)
├── storage/najm-hoda/  # Knowledge Base for AI training
├── resources/views/    # Unified Blade & Tailwind templates
├── routes/             # Segmented routes (API, Web, Admin)
└── database/           # 54+ Models & scalable schema migrations
```

---

## 🔒 Security & Roles

- **RBAC:** Advanced Role-Based Access Control (Admin, Moderator, Leader, User).
- **Sanctum:** Secure API token management.
- **CSRF & XSS Protection:** Native Laravel security hardening.

---

## 🤝 Contributing

We welcome developers, visionaries, and cooperation experts!

1. Fork the Project.
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`).
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the Branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<p align="center">
  Built with ❤️ for a smarter coop world.
</p>
