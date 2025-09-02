# Two-Wheeler Rental System

## Project Overview

The Two-Wheeler Rental System is a robust web application designed to streamline the process of renting two-wheelers. It provides a user-friendly interface for customers to browse, select, and book bikes, while offering a powerful admin panel for efficient management of the rental operations. The system prioritizes security, real-time data processing, and comprehensive reporting to ensure a seamless experience for both users and administrators.

## Key Features

### User Management
- **User Registration and Login:** Secure user authentication with password hashing and session management.
- **User Sessions:** Maintains secure user sessions to personalize interactions and protect sensitive data.

### Booking System
- **Dynamic Bike Display:** PHP dynamically displays available bikes based on selected rental dates, ensuring users only see what's truly available.
- **Real-time Booking Status:** Prevents double bookings by updating bike availability in real-time.
- **Booking Requests:** Users can easily submit booking requests for their desired two-wheelers.

### Admin Panel
- **Bike Management:**
    - **Add Bike Listings:** Administrators can effortlessly add new bikes to the inventory, including details like model, make, availability, and rental price.
    - **Edit Bike Listings:** Update existing bike information, such as price changes or status updates.
    - **Delete Bike Listings:** Remove bikes from the system.
- **Booking Management:**
    - **View All Bookings:** Comprehensive overview of all active and past bookings.
    - **Update Booking Status:** Admins can change booking statuses (e.g., confirmed, cancelled, completed).
- **User Management:**
    - **View All Users:** List all registered users.
    - **Edit User Roles:** Change user roles (user/admin).
    - **Delete Users:** Remove user accounts from the system.
- **Reporting and Analytics:** Generate reports on popular bikes, revenue, and user activity to aid business decisions (conceptual, not fully implemented in current code).
- **User Feedback:** Efficiently handle and respond to user inquiries and feedback (conceptual, not fully implemented in current code).

## Technical Specifications & System Architecture

The system follows a client-server architecture. The frontend, built with HTML, CSS, and JavaScript, interacts with the backend PHP scripts. PHP communicates with the MySQL database to retrieve and store data. The admin panel is also built using PHP and interacts with the same backend logic and database.

### Technology Stack

-   **Backend:** PHP (version 7.4+ recommended)
    -   Used for dynamic content generation, business logic, and database interactions.
    -   `mysqli` extension is used for connecting to the MySQL database and executing queries, specifically with prepared statements for security.
-   **Database:** MySQL
    -   Stores all system data: user information, bike details, bookings, etc.
    -   See `database/schema.sql` for the detailed schema.
-   **Frontend:**
    -   **HTML:** Structures the web content and forms.
    -   **CSS:** Styles the application for a modern and responsive look.
    -   **JavaScript:** Provides client-side interactivity and asynchronous operations (currently minimal, primarily for form interactions).
-   **Security Considerations:**
    -   **Password Hashing:** Passwords are hashed using `password_hash()` (e.g., `PASSWORD_DEFAULT`) before storage to prevent plain-text password exposure.
    -   **Prepared Statements:** All database interactions utilize prepared statements to guard against SQL injection attacks.
    -   **Session Management:** PHP sessions are used to manage user login states securely.
    -   **Input Validation:** Basic server-side input validation is implemented for form submissions (e.g., email format, password length, date validity) to enhance security and data integrity.
    -   **Role-Based Access Control:** Access to admin-specific pages is restricted to users with the 'admin' role.

### Database Design (Key Tables from `database/schema.sql`)

*   **`users` Table:**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique user identifier.
    *   `name` (VARCHAR): User's full name.
    *   `email` (VARCHAR, UNIQUE): User's email, used for login, must be unique.
    *   `password` (VARCHAR): Hashed password.
    *   `role` (ENUM('user', 'admin')): User's role in the system.
    *   `created_at` (TIMESTAMP): Timestamp of user registration.
*   **`bikes` Table:**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique bike identifier.
    *   `make` (VARCHAR): Manufacturer of the bike.
    *   `model` (VARCHAR): Model name of the bike.
    *   `year` (INT): Manufacturing year.
    *   `license_plate` (VARCHAR, UNIQUE): Unique license plate number.
    *   `rental_price_per_day` (DECIMAL): Daily rental cost.
    *   `availability_status` (ENUM('available', 'rented', 'maintenance')): Current status of the bike.
    *   `image_url` (VARCHAR, NULLABLE): URL to an image of the bike (currently not implemented for uploads).
    *   `created_at` (TIMESTAMP): Timestamp of bike listing creation.
*   **`bookings` Table:**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique booking identifier.
    *   `user_id` (INT, FOREIGN KEY `REFERENCES users(id)`): Links to the user who made the booking.
    *   `bike_id` (INT, FOREIGN KEY `REFERENCES bikes(id)`): Links to the booked bike.
    *   `start_date` (DATE): Start date of the rental.
    *   `end_date` (DATE): End date of the rental.
    *   `total_price` (DECIMAL): Calculated total price for the rental period.
    *   `booking_status` (ENUM('pending', 'confirmed', 'cancelled', 'completed')): Current status of the booking.
    *   `created_at` (TIMESTAMP): Timestamp of booking creation.

### Why SQL (MySQL) over NoSQL?

MySQL was chosen for this project because:

1.  **Structured and Relational Data:** The core data (users, bikes, bookings) is inherently structured with clear, well-defined relationships. MySQL, as an RDBMS, is optimized for managing such relational data and maintaining data integrity through foreign keys.
2.  **Data Integrity and ACID Properties:** A rental system requires strong data consistency. MySQL enforces ACID (Atomicity, Consistency, Isolation, Durability) properties, which are crucial for transactional operations like bookings. This prevents issues like double bookings and ensures that the database remains in a valid state after every transaction.
3.  **Complex Querying and Reporting:** SQL provides a powerful and flexible language for complex queries, aggregations, and joins across multiple tables. This is essential for features like finding available bikes within a date range or generating detailed admin reports.
4.  **Mature Ecosystem and Tooling:** MySQL has a vast, mature ecosystem with extensive documentation, tools (like phpMyAdmin), and community support, simplifying development, administration, and troubleshooting.

NoSQL databases, while excellent for unstructured data, high write throughput, and eventual consistency, were not suitable for the core requirements of this project which prioritize transactional integrity and complex relational queries.

## Getting Started with GitHub

Follow these steps to push your project to GitHub:

1.  **Initialize a Git Repository (if you haven't already):**
    Open your terminal or command prompt, navigate to your project's root directory (`cd C:\Users\91982\Desktop\two wheeler rental`), and run:
    ```bash
    git init
    ```

2.  **Add Your Project Files to the Repository:**
    This command stages all your files for the first commit.
    ```bash
    git add .
    ```

3.  **Commit Your Changes:**
    This creates a snapshot of your project's current state.
    ```bash
    git commit -m "Initial commit: Two-Wheeler Rental System project"
    ```

4.  **Create a New Repository on GitHub:**
    -   Go to [GitHub](https://github.com/).
    -   Log in to your account.
    -   Click the '+' icon in the top right corner and select "New repository."
    -   Give your repository a name (e.g., `two-wheeler-rental-system`).
    -   Add a description (optional).
    -   Choose between "Public" or "Private."
    -   **Do NOT initialize with a README, .gitignore, or license** as you're pushing an existing project.
    -   Click "Create repository."

5.  **Connect Your Local Repository to GitHub:**
    After creating the repository on GitHub, you'll see instructions. You'll typically use these two commands. Replace `<YOUR_GITHUB_USERNAME>` and `<YOUR_REPOSITORY_NAME>` with your actual details.
    ```bash
    git remote add origin https://github.com/<YOUR_GITHUB_USERNAME>/<YOUR_REPOSITORY_NAME>.git
    git branch -M main
    ```
    *If your default branch is `master`, you can use `git branch -M master` instead of `git branch -M main`.*

6.  **Push Your Project to GitHub:**
    This command uploads your local repository's committed changes to the GitHub repository.
    ```bash
    git push -u origin main
    ```
    *Again, if your branch is `master`, use `git push -u origin master`.*

7.  **Verify on GitHub:**
    Refresh your GitHub repository page. You should now see all your project files there.

---

Remember to regularly commit and push your changes as you continue developing:

```bash
git add .
git commit -m "Descriptive commit message"
git push origin main # or master
```

## Deployment Guide

This section provides instructions on how to deploy and make your Two-Wheeler Rental System accessible. You'll need a web server environment that supports PHP and MySQL.

### 1. Prerequisites

Before you begin, ensure you have the following:

*   **Web Server:** Apache or Nginx are common choices.
*   **PHP:** Version 7.4 or higher is recommended.
*   **MySQL Database:** A running MySQL server.
*   **A Text Editor/IDE:** (Like Cursor, VS Code, etc.)
*   **Web Browser:** To access your application.

### 2. Database Setup

This is a critical step to get your application running.

1.  **Start MySQL Server:** Ensure your MySQL server is running.
2.  **Access MySQL:** Use a tool like phpMyAdmin, MySQL Workbench, or the command line client.
3.  **Create Database:** If you haven't already, create the database:
    ```sql
    CREATE DATABASE IF NOT EXISTS `two_wheeler_rental`;
    ```
4.  **Import Schema:** Import the provided SQL schema to create the necessary tables.
    *   **Using phpMyAdmin:**
        1.  Select the `two_wheeler_rental` database.
        2.  Go to the "Import" tab.
        3.  Click "Choose File" and select `database/schema.sql` from your project directory.
        4.  Click "Go."
    *   **Using MySQL Command Line:**
        ```bash
        mysql -u your_mysql_username -p two_wheeler_rental < database/schema.sql
        ```
        (Replace `your_mysql_username` with your MySQL username. You'll be prompted for the password.)
5.  **Update Database Credentials:** Open `includes/db_connect.php` and ensure the `$host`, `$user`, `$password`, and `$database` variables match your MySQL server configuration.

    ```php
    // includes/db_connect.php

    $host = 'localhost'; // Your database host (e.g., 'localhost' or an IP address)
    $user = 'root';      // Your database username
    $password = '';  // Your database password (empty if no password set)
    $database = 'two_wheeler_rental'; // Your database name
    ```

### 3. Local Development Environment (e.g., XAMPP, WAMP, MAMP)

For local testing, packages like XAMPP (Windows, Linux, macOS), WAMP (Windows), or MAMP (macOS) provide an all-in-one solution with Apache, MySQL, and PHP.

1.  **Install XAMPP/WAMP/MAMP:** Download and install your preferred package.
2.  **Start Services:** Start the Apache and MySQL services from the control panel.
3.  **Place Project Files:** Copy your entire `two wheeler rental` project folder into the web server's document root:
    *   **XAMPP:** `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (macOS)
    *   **WAMP:** `C:\wamp64\www\`
    *   **MAMP:** `/Applications/MAMP/htdocs/`
4.  **Rename Folder (Optional but Recommended):** You can rename the `two wheeler rental` folder to something simpler like `tworental`.
5.  **Access Locally:** Open your web browser and navigate to:
    *   `http://localhost/two wheeler rental/` (or `http://localhost/tworental/` if you renamed it)

### 4. Remote Server Deployment (Shared Hosting, VPS, Cloud)

Deploying to a live server typically involves uploading your files and configuring the server.

1.  **Choose a Hosting Provider:** Select a hosting provider (e.g., HostGator, Bluehost, AWS, DigitalOcean, Azure) that supports PHP and MySQL.
2.  **Upload Files:**
    *   **FTP/SFTP:** Use an FTP client (like FileZilla) to upload your entire `two wheeler rental` project folder to the `public_html` (or equivalent) directory of your hosting account.
    *   **Git (for VPS/Cloud):** If you're using a VPS or cloud provider, you might use Git to clone your repository directly onto the server.
3.  **Database Setup on Remote Server:**
    *   Most hosting providers offer a control panel (e.g., cPanel) where you can create a MySQL database and a database user.
    *   **Crucially, ensure the database user has all privileges on your newly created database.**
    *   Import your `database/schema.sql` file using the hosting provider's tools (e.g., phpMyAdmin via cPanel).
4.  **Update `includes/db_connect.php` for Remote Server:**
    *   **This is critical!** The `$host`, `$user`, `$password`, and `$database` will almost certainly be different on your remote server. Update `includes/db_connect.php` with the credentials provided by your hosting provider.
5.  **Configure Web Server (for VPS/Cloud):** If you are on a VPS or cloud instance, you might need to configure your Apache or Nginx web server to point to your project's `public` directory. This step is usually handled automatically by shared hosting providers.
6.  **Set File Permissions:** Ensure your web server has appropriate read/write permissions for your project files. This is particularly important if you plan to implement file uploads (which are not currently in the project but would require write access). Consult your hosting provider's documentation for recommended permissions.

### 5. Accessing Your Deployed Application

Once deployed, you can access your application via your domain name.

*   **Local:** `http://localhost/your-project-folder-name/`
*   **Remote:** `http://your-domain.com/` or `http://your-domain.com/your-project-folder-name/` (depending on how you set up your hosting)

---

**Important Security Note:** For a production environment, you should consider more robust security measures such as:
*   Using HTTPS.
*   More comprehensive input validation and sanitization.
*   Error logging instead of displaying errors directly to users.
*   Regular security audits and updates of your server and dependencies.
*   Strong, unique passwords for all database users and admin accounts.

## Interview Questions (100 Questions)

This section provides a comprehensive list of potential interview questions covering various aspects of the Two-Wheeler Rental System. These questions are designed to test your understanding of the project's architecture, technologies, implementation details, and problem-solving skills.

### General Project Understanding

1.  What is the primary purpose of the Two-Wheeler Rental System?
2.  Who are the target users of this system?
3.  What are the core functionalities offered by the system?
4.  Can you describe the overall architecture of the application?
5.  What technologies did you use to build this project and why?
6.  What were the biggest challenges you faced during development and how did you overcome them?
7.  How does this project demonstrate your skills as a developer?
8.  If you had more time, what additional features would you implement?
9.  How would you scale this application to handle a large number of users?
10. What are the key security considerations you addressed in this project?

### User Management and Authentication

11. How do you handle user registration? What data is collected?
12. Describe the user login process.
13. How are user passwords stored securely? (e.g., hashing algorithms used)
14. Explain how user sessions are managed.
15. What measures are in place to prevent brute-force attacks on login?
16. How do you handle forgotten passwords?
17. What is the role of session management in securing user data?
18. Can a user have multiple active sessions? How is this handled?
19. How do you validate user input during registration and login?
20. What is the difference between authentication and authorization in this system?

### Booking System

21. How does the system dynamically display available bikes?
22. What database queries are involved in fetching available bikes for a specific date range?
23. Explain the real-time booking status update mechanism.
24. How do you prevent double bookings?
25. What happens when a user submits a booking request? Describe the flow.
26. How are booking conflicts resolved?
27. What information is stored for each booking?
28. How do you handle booking cancellations?
29. Is there a booking confirmation process? If so, how does it work?
30. How do you manage the life cycle of a booking (pending, confirmed, completed, cancelled)?

### Admin Panel

31. What functionalities are available in the admin panel?
32. How do administrators authenticate to the admin panel?
33. Describe the process of adding a new bike listing.
34. What details are required when adding or editing a bike?
35. How can an administrator view all current and past bookings?
36. How do administrators update the status of a booking?
37. What kind of reports can be generated from the admin panel?
38. How do these reports help in business decision-making?
39. How is user feedback handled and tracked in the admin panel?
40. What security measures are in place for the admin panel to prevent unauthorized access?

### Database Design and Management

41. Describe the main tables in your MySQL database schema.
42. What are the relationships between these tables? (e.g., one-to-many, many-to-many)
43. How do you ensure data integrity in your database?
44. Explain your indexing strategy for improved database performance.
45. How do you handle concurrent access to the database, especially during booking?
46. What is your backup strategy for the database?
47. How would you optimize slow database queries?
48. What are the advantages of using MySQL for this project?
49. How do you connect PHP to the MySQL database? (e.g., PDO, mysqli)
50. Describe any stored procedures or triggers you might use.

### PHP Specifics

51. How do you handle routing in your PHP application?
52. What PHP frameworks or libraries did you use (if any)?
53. How do you ensure secure coding practices in PHP?
54. Explain how PHP interacts with the HTML frontend to display dynamic content.
55. How do you manage errors and exceptions in PHP?
56. What superglobals did you use and for what purpose? (e.g., `$_POST`, `$_SESSION`)
57. How do you prevent SQL injection vulnerabilities in your PHP code?
58. Describe your approach to input validation and sanitization in PHP.
59. How do you handle file uploads (if any) securely in PHP?
60. Explain the use of `include` or `require` statements in your project structure.

### Frontend (HTML, CSS, JavaScript)

61. How did you structure your HTML for accessibility and semantic meaning?
62. What CSS frameworks or methodologies did you employ (if any)?
63. How is JavaScript used to enhance the user experience?
64. Describe how the frontend communicates with the backend (e.g., AJAX).
65. How do you handle form submissions on the frontend?
66. What steps did you take to make the frontend responsive?
67. How do you ensure cross-browser compatibility?
68. What are some performance optimizations you considered for the frontend?
69. How do you handle client-side validation?
70. What is the role of JavaScript in the dynamic display of bikes?

### Security

71. Beyond password hashing, what other security measures are in place?
72. How do you protect against Cross-Site Scripting (XSS) attacks?
73. How do you protect against Cross-Site Request Forgery (CSRF) attacks?
74. Explain the importance of input validation and sanitization for security.
75. How do you manage sensitive data securely within the application?
76. What are common web vulnerabilities and how did you mitigate them?
77. How do you handle error messages to avoid revealing sensitive information?
78. What is the principle of least privilege, and how is it applied?
79. How do you keep your dependencies (libraries, frameworks) secure and up-to-date?
80. Describe your approach to security testing.

### Deployment and Operations

81. How would you deploy this application to a production environment?
82. What web server would you recommend (e.g., Apache, Nginx) and why?
83. How would you monitor the application's performance and health?
84. What logging mechanisms are in place?
85. How would you troubleshoot issues in a live environment?
86. What is your continuous integration/continuous deployment (CI/CD) strategy?
87. How do you manage environment variables and sensitive configurations?
88. What considerations are there for scaling the application vertically and horizontally?
89. How would you handle disaster recovery for this system?
90. What tools would you use for system administration?

### Advanced Topics & Best Practices

91. How would you implement an API for this system for mobile applications?
92. Describe how you would integrate a payment gateway.
93. How would you add a notification system (e.g., email, SMS) for bookings?
94. What design patterns did you use or consider during development?
95. How do you ensure code maintainability and readability?
96. What testing methodologies did you apply (e.g., unit, integration)?
97. How do you document your code?
98. What version control system are you using and why?
99. How do you handle potential deadlocks in the database?
100. What is your approach to code reviews?
