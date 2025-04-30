# ChoreForce

## Installation Instructions

### 1. Install XAMPP
- Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/).
- Ensure the **Apache** and **MySQL** modules are running via the XAMPP Control Panel.

---

## Setup and Configuration

### 2. Database Setup
1. Open **phpMyAdmin** by visiting: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).

2. Place the entire `choreforce` folder (with all files) into: C:\xampp\htdocs\choreforce

3. Import the `create.sql` file:
- Ensure **foreign key checks** and **auto increments** are disabled before importing.

4. Edit `load.sql`:
- Make sure all file paths to the mock data files (`USER.csv`, `PARENT.csv`, `CHILD.csv`, `CHORE.csv`, `PAYMENT.csv`) are correct relative to your local file structure.

5. Import the `load.sql` file:
- Again, ensure **foreign key checks** and **auto increments** are disabled.

---

## Access the System

### 3. Launch ChoreForce
- Open your web browser and go to: http://localhost/choreforce/landingpage.html
- The landing page of **ChoreForce** should now be displayed.

---

## You're Ready to Go!
ChoreForce should now be fully set up and ready for use.


