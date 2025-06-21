# ✅ Task Planner - PHP Assignment

I have completed the assignment as per the provided instructions.

---

## 📁 How to Run the Project Locally

### 🧑‍💻 1. Start the PHP Server

- Clone or download this repository (`Task-Planner`) to your local system.
- Open the terminal in **VS Code**.
- Navigate to the root folder (`/task-planner`).
- Run the following command to start the PHP development server:

```bash
php -S localhost:8000 -t src
```

- This will start the server at `http://localhost:8000`

---

### 📬 2. Setup MailHog for Local Email Testing

MailHog is used to catch emails sent locally (like verification emails).

#### 🔽 Download MailHog:

👉 [MailHog Windows (64-bit)](https://github.com/mailhog/MailHog/releases/download/v1.0.1/MailHog_windows_amd64.exe)

1. Place it inside `C:\mailhog`
2. Add this folder to your system’s **Environment Variables > Path**

#### 🚀 Start MailHog

Open a **new terminal** and run:

```bash
cd C:\mailhog
mailhog
```

Then visit:  
👉 `http://localhost:8025`  
⚠️ Avoid using `http://0.0.0.0:8025/`

---

### ⏰ 3. Start Reminder Cron Script

This script sends **hourly reminders** to verified subscribers if tasks are pending.

In another terminal window, run:

```bash
php src/cron.php
```

---

## ✅ Assignment Workflow

### Step-by-Step:

1. User opens `http://localhost:8000`
2. Adds a task using the Task Planner interface
3. Enters their email to subscribe
4. A **verification email** is sent (viewed in MailHog)
5. User clicks on "Verify Subscription" link from MailHog
6. Their email is moved from `pending_subscriptions.txt` to `subscribers.txt`
7. If any task remains incomplete, they receive reminder emails every hour

---

## 📂 Files Updated in This Assignment

- ✅ `tasks.txt` – stores all tasks
- ✅ `pending_subscriptions.txt` – stores unverified subscribers
- ✅ `subscribers.txt` – stores verified users
- ✅ `cron.php` – sends reminders
- ✅ `verify.php` – handles email verification

---

## 📌 Final Notes
- This Task-Schedular project is named Task Planner by me
- Assignment is developed using **PHP 8.3**
- All storage is in `.txt` files using **JSON format**
- No database or external libraries used
- Follows the exact specification as per assignment guidelines

---

## 🙌 Thank You!

This project was built and tested completely in a local environment using PHP and MailHog. Feel free to explore the source code inside the `src/` folder.
