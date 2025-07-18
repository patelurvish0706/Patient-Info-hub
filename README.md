# 🏥 Patient Information Management System (PIMS)

### 📸Screenshots: [https://github.com/patelurvish0706/Patient-Info-hub/blob/main/Screenshots.md]

PIMS is a complete web-based healthcare management solution designed for hospitals to streamline appointment scheduling, doctor-patient interactions, nurse coordination, and report tracking — all while ensuring role-based access and data security.

## 🚀 Features
### 🧑‍⚕️ Admin Panel
- Register or log in with a valid credential.
- Create and manage a hospital profile.
- provide credential to doctor and department(nurse).

- ### 🧑‍⚕️ Doctor Panel
- Register or log in with a hospital ID provided by Admin.
- View and manage verified appointments.
- Add prescriptions, checkup outcomes, and suggestions post-consultation.

### 🧑‍⚕️ Nurse Panel
- Login with credentials generated by a admin.
- Approve or reject appointment requests by user.
- Mark attendence of petients for appointment. 

### 🧑 Patient Portal
- Register and login independently (no hospital ID required).
- Book appointments with basic symptoms.
- Receive reminders post-appointment.
- View medical history, prescriptions, and reports.

## 🔐 Authentication & Roles

- **Admin**: Provide credentials to doctors and nurse.
- **Doctors**: Department-based login (not self-registered).
- **Nurses**: Login credentials are generated by admin (not self-registered).
- **Patients**: Simple independent registration.


## 🗃️ Database Structure

The project uses a MySQL database with multiple interrelated tables:

- `appointments`
- `approval`
- `visit`
- `report`
- `departments`
- `doctors`
- `nurses`
- `user_details` (Patient)
- `hospitals`

Proper use of **foreign keys**, **enums**, and **auto-incremented IDs** ensures integrity and scalability.

## 🛠️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (vanilla)
- **Database**: MySQL
- **Hosting**: Localhost / XAMPP


## 📦 Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/pims.git
   cd pims
   ```
2. **Import the database:**

   * Open `phpMyAdmin`
   * Create a database named `pims`
   * Import the provided `.sql` file from `/database/pims.sql`

3. **Configure DB connection:**
   Edit `script/db_connection.php` with your local database credentials. <br>
   import database structure by importing `127_0_0_1.sql`.

5. **Start Apache and MySQL**, then open in your browser:

   ```
   http://localhost/pims/
   ```

---

## 🧪 Sample Credentials to register and login

| Role         | Email / ID                                      | Password         |
| ------------ | ----------------------------------------------- | ---------------- |
| Admin        | admin@gmail.com                                 | 11111111         |
| Department   | dept1@gmail.com                                 | 11111111         |
| Doctor       | doc1@gmail.com                                  | 11111111         |
| User-Patient | user@gmail.com                                  | 11111111         |


## 📸 Screenshots

*Coming soon...*

## 🤝 Contributions

Contributions, suggestions, and issue reports are welcome!
Please open a [pull request](https://github.com/your-username/pims/pulls) or create an [issue](https://github.com/your-username/pims/issues).

## 📬 Contact

Created by Urvish Patel
📧 Email: [patelurvish0706@gmail.com](mailto:patelurvish0706@gmail.com)
📌 GitHub: [@patelurvish0706](https://github.com/patelurvish0706)
