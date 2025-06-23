# Livewire & Reverb Real-time Chat Application

A modern, real-time chat application built with Laravel Livewire for dynamic interfaces, powered by Laravel Reverb for efficient WebSocket communication, and styled with Tailwind CSS for a sleek and aesthetic user experience.

## ✨ Features

-   **User Authentication:** Secure user registration and login.
-   **Real-time Messaging:** Instant, low-latency message delivery using WebSockets.
-   **Private Chat:** Dedicated one-on-one conversations between authenticated users.
-   **User List:** Easily select and chat with other registered users.
-   **Dynamic Chat Interface:** Messages load and update seamlessly without page reloads.
-   **Responsive Design:** Optimized for various screen sizes, from mobile to desktop.

## 🚀 Technologies Used

-   **Laravel 11/12:** The robust PHP Framework.
-   **Laravel Livewire v3:** A powerful full-stack framework for building dynamic interfaces.
-   **Laravel Reverb:** Laravel's official, high-performance WebSocket server.
-   **Laravel Sanctum:** For API authentication, essential for private channel authorization with Reverb.
-   **Tailwind CSS:** A utility-first CSS framework for rapid and customizable styling.
-   **MySQL (or your preferred RDBMS):** For data persistence.

## 📦 Installation

Follow these steps to set up and run the project on your local machine.

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & npm (or Yarn)
-   A database server (e.g., MySQL, PostgreSQL, SQLite)

### Steps

1.  **Clone the repository:**

    ```bash
    git clone [https://github.com/your-username/your-repo-name.git](https://github.com/your-username/your-repo-name.git)
    cd your-repo-name
    ```

2.  **Install PHP Dependencies:**

    ```bash
    composer install
    ```

3.  **Create Environment File:**

    ```bash
    cp .env.example .env
    ```

4.  **Configure `.env` File:**
    Open the newly created `.env` file and update the following variables:

    -   **Application Key:**

        ```bash
        php artisan key:generate
        ```

        (This command will automatically set `APP_KEY` in your `.env` file.)

    -   **Database Credentials:**

        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password
        ```

        (Replace placeholders with your actual database details.)

    -   **Broadcasting and Reverb Configuration:**

        ```env
        BROADCAST_DRIVER=reverb
        REVERB_APP_KEY="your_reverb_app_key_uuid" # This key is found in `config/reverb.php`
                                                 # or is generated/updated when running `php artisan install:broadcasting`.
        REVERB_HOST="127.0.0.1" # Or your machine's local IP if accessing from another device
        REVERB_PORT=8080 # Default Reverb port
        REVERB_SCHEME=http # Use `http` for local development (WebSocket connection will be `ws://`)
                           # Use `https` if you've configured SSL/TLS for Reverb (WebSocket connection will be `wss://`)
        ```

5.  **Run Database Migrations & Seeders (Optional for dummy data):**

    ```bash
    php artisan migrate:fresh --seed # This will migrate your database and run seeders (if defined)
    ```

    If you want to quickly create some dummy users for testing the chat:

    ```bash
    php artisan tinker
    App\Models\User::factory()->count(5)->create(); # Creates 5 dummy users
    exit;
    ```

6.  **Install Broadcasting & Frontend Dependencies:**
    This command will set up Laravel Echo, Pusher.js (which Reverb uses internally), and configure the necessary service providers.

    ```bash
    php artisan install:broadcasting
    ```

    When prompted, make sure to select `reverb` as your broadcasting driver.

7.  **Install Node Modules:**

    ```bash
    npm install
    # OR if you prefer Yarn:
    # yarn install
    ```

8.  **Compile Frontend Assets:**

    ```bash
    npm run dev
    # For production deployment, use:
    # npm run build
    ```

9.  **Start Laravel Development Server:**

    ```bash
    php artisan serve
    ```

10. **Start Laravel Reverb Server:**
    **Open a new terminal window** (keep the `php artisan serve` terminal running) and execute:

    ```bash
    php artisan reverb:start
    ```

    Keep this terminal open as long as you want your real-time chat features to function.

## 🚀 Usage

1.  **Access the Application:** Open your web browser and navigate to `http://127.0.0.1:8000`.

2.  **Register/Login:** Create at least two user accounts to fully test the chat functionality.

3.  **Navigate to Chat:** Access the main chat interface (typically via a dashboard link, or directly if you've set up a route).

4.  **Select a User:** Click on a user to open a conversation with them.

5.  **Send Messages:** Type your message in the input field at the bottom and click "Send." Observe messages appearing instantly for both the sender and the receiver.

6.  **Test Real-time Functionality:** Open two separate browser windows or tabs, log in as different users in each, and initiate a conversation. Messages should appear in real-time across both interfaces.

## 🤝 Contributing

Contributions are welcome! If you find a bug or have a feature request, please open an issue. For direct contributions, feel free to fork the repository and submit a pull request.

1.  Fork the Project

2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)

3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)

4.  Push to the Branch (`git push origin feature/AmazingFeature`)

5.  Open a Pull Request

## 📄 License

This project is open-source software licensed under the [MIT license](LICENSE).
