# Hospital Management System

A web-based **Hospital Management System** built with PHP, designed to streamline hospital operations such as appointments, billing, and staff management.

---

## 📋 Features

* **User Authentication**: Secure login for doctors, nurses, and administrative staff.
* **Appointment Scheduling**: Patients can book, view, and manage appointments. Automated email notifications for upcoming visits.
* **Billing Module**: Generate and process patient bills with payment status tracking.
* **Dashboard Views**:

  * Doctor Dashboard for managing schedules and patient records.
  * Nurse Dashboard for monitoring patient vitals and assignments.
  * Admin Dashboard for overall system control.
* **Role-Based Access Control**: Permissions tailored to each user type.

---

## 💻 Tech Stack

* **Backend**: PHP 7.x
* **Database**: MySQL
* **Frontend**: HTML5, CSS3, JavaScript

---

## ⚙️ Installation & Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/hospital-management-system.git
   cd hospital-management-system
   ```
2. Configure database:

   * Create a MySQL database (e.g., `hospital_db`).
   * Import the schema from `database/schema.sql`.
   * Update database credentials in `connect.php`.
3. Deploy on a local server (e.g., XAMPP/WAMP):

   * Place project folder in `htdocs` or `www`.
   * Start Apache & MySQL services.
4. Access the application at `http://localhost/hospital-system/`.

---

## 🤝 Contributing

Contributions are welcome! Please fork the repo and open a pull request with your changes.

---

*Happy coding!*
