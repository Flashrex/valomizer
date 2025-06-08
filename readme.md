# Valomizer

This is a fanmade Valorant application and is not in any way associated with Valorant or Riot Games.

---

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Inertia.js, Vue 3
- **Styling:** Tailwind CSS
- **Build Tool:** Vite
- **Containerization:** Docker (Laravel Sail)
- **Database:** MongoDB

---

## Environment Setup

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) (for Laravel Sail)
- [Node.js](https://nodejs.org/) (v18+ recommended)
- [npm](https://www.npmjs.com/) (comes with Node.js)
- [PHP](https://www.php.net/) (v8.2+ if running outside Docker)
- [Composer](https://getcomposer.org/) (if running outside Docker)
- [Git](https://git-scm.com/)

---

## Setting up Local Development Environment

1. **Clone the Repository**
    ```sh
    git clone https://github.com/Flashrex/valomizer.git
    cd valomizer
    ```

2. **Copy Environment File**
    ```sh
    cp example.env .env
    ```
    Update the `.env` file with your local configuration as needed.

3. **Install Composer Dependencies**
    ```sh
    ./vendor/bin/sail composer install --ignore-platform-req=ext-mongodb
    ```

4. **Start Docker Containers (with Sail)**
    ```sh
    ./vendor/bin/sail up -d
    ```

5. **Install Node.js Dependencies**
    ```sh
    sail npm install
    ```

8. **Build Frontend Assets**
    ```sh
    npm run dev
    ```

---

## Setting up Production Server

1. **Provision a Server**
    - Ubuntu 22.04+ recommended
    - Install Docker, Docker Compose, and Git

2. **Clone the Repository**
    ```sh
    git clone https://github.com/Flashrex/valomizer.git
    cd valomizer
    ```

3. **Set Up Environment Variables**
    - Copy `.env` and update for production (set `APP_ENV=production`, `APP_URL`, etc.)

4. **Install Composer Dependencies**
    ```sh
    composer install --ignore-platform-req=ext-mongodb
    ```

5. **Start Docker Containers**
    ```sh
    ./vendor/bin/sail up -d
    ```

6. **Install Node.js Dependencies & Build Assets**
    ```sh
    npm install
    npm run build
    ```

9. **Configure Web Server**
    - Point your web server (Nginx/Apache) to the `public` directory.
    - Ensure HTTPS is enabled.

---

## Contribution & Merge Rules

Before pushing or merging to the `main` branch, **you must**:

1. **Run PHP code style fixer:**
    ```sh
    ./vendor/bin/sail pint
    ```

2. **Format frontend code:**
    ```sh
    ./vendor/bin/sail npm run format
    ```

3. **Run frontend linter:**
    ```sh
    ./vendor/bin/sail npm run lint
    ```

Only merge if all checks pass and your code is properly formatted and linted.

---

## Notes

- This project is not affiliated with or endorsed by Riot Games.
- For development, use Sail for a consistent Docker-based environment. It is recommended to use ubuntu or wsl.
- For production, ensure all environment variables are set securely and HTTPS is enforced.

---