# Choreforce

A chore and allowance tracking system for families.

## 📦 Installation Instructions

### 1. Install XAMPP
- Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
- Open the XAMPP control panel.
- Ensure **Apache** and **MySQL** modules are running.

### 2. Setup and Configuration
- After installation, a `xampp` folder should appear in your file system.
- Create a folder named `choreforce` in `C:\xampp\htdocs\`.
- Copy **all HTML and PHP files** from this GitHub repository into the `choreforce` folder.

### 3. Database Setup
- Open [phpMyAdmin](http://localhost/phpmyadmin).
- Create a new database named `choreforce`  
  ⚠️ Ensure the database name matches the folder name exactly, including case.

#### Import `create.sql`:
- Click on the `choreforce` database.
- Select the `Import` tab.
- Upload `create.sql`.
- Before importing, **untoggle** the following options:
  - Foreign key checks
  - Partial imports
  - Auto increment settings

#### After importing `create.sql`:
- Open `load.sql` in a text editor.
- Ensure the file paths for mock data (`USER.csv`, `PARENT.csv`, `CHILD.csv`, `CHORE.csv`, `PAYMENT.csv`) are correct relative to your current file structure.

#### Import `load.sql`:
- Go back to phpMyAdmin and import `load.sql` using the same settings (untoggle foreign key checks, partial imports, and auto increments).

### 4. Access the System
- Open a web browser and go to:  
  [http://localhost/choreforce/landingpage.html](http://localhost/choreforce/landingpage.html)

You should see the **Choreforce Landing Page**.

- You can log in using an existing user (username = `ID`, password = `Password`)  
  — or —  
  Create a new account and begin using the system.

---

## 🧭 System Usage

### 🔐 Login Screen
- Parent or Child enters credentials to access their portal.

### 👨‍👩‍👧 Parent Portal
- View all assigned chores for children.
- Navigate to:
  - **Manage Chores**
  - **Manage Children**
  - **Manage Payments**

#### 📋 Manage Chorelist
- Delete chores by `ChoreID`.
- Navigate to:
  - **Add a Chore**
  - **Edit a Chore**

#### ➕ Add Chore
- Assign a new chore to a child.

#### ✏️ Edit Chore
- Edit the **Reward Amount** or **Description** of a chore.

#### 👧 Manage Children
- Delete children by `ChildID`.
- Navigate to:
  - **Add Child**
  - **Edit Child**

#### 👶 Add Child
- Add a new child to the system.

#### 📝 Edit Child
- Edit the **Child’s Name**.

#### 💵 Manage Payments
- Create and view all past payments.

### 🧒 Child Portal
- View all assigned chores.
- Mark chores with status **"Pending"** as **"Complete"**.
- View **Total Earned Amount**.
