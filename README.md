# 🎵 SVARA — Musical Instruments E-Commerce Platform

A full-featured e-commerce web application for musical instruments, built with PHP, MySQL, and vanilla JavaScript.

---

## 🚀 Live Demo

> Run locally using XAMPP (see setup instructions below)

---

## 📸 Screenshots

<img width="1366" height="768" alt="Screenshot (112)" src="https://github.com/user-attachments/assets/289c1582-45eb-4a25-af89-15e1402d59c7" />
<img width="1366" height="768" alt="Screenshot (111)" src="https://github.com/user-attachments/assets/500681d2-a2a4-45dd-95e7-f3f5dc102108" />
<img width="1366" height="768" alt="Screenshot (110)" src="https://github.com/user-attachments/assets/2ffdab4b-acc6-4369-aad4-a348e8c5232e" />
<img width="1366" height="768" alt="Screenshot (109)" src="https://github.com/user-attachments/assets/d9d26221-8d80-47fb-af2b-01e84f8f785f" />
<img width="1366" height="768" alt="Screenshot (108)" src="https://github.com/user-attachments/assets/a5ee4727-83f7-4b73-ad10-76070a3c95d2" />
<img width="1366" height="768" alt="Screenshot (119)" src="https://github.com/user-attachments/assets/93e9b8f0-c39e-45fa-8c58-01a110b3e464" />
<img width="1366" height="768" alt="Screenshot (117)" src="https://github.com/user-attachments/assets/89281fab-7533-48f5-a443-b7f62e9bfe3b" />
<img width="1366" height="768" alt="Screenshot (116)" src="https://github.com/user-attachments/assets/ba658c65-6b5c-47c8-918b-24054bde083a" />
<img width="1366" height="768" alt="Screenshot (115)" src="https://github.com/user-attachments/assets/9bc95a93-7d1a-4b3b-9f19-04e451fdb5b5" />
<img width="1366" height="768" alt="Screenshot (114)" src="https://github.com/user-attachments/assets/2bad0663-3c6e-4e6f-9787-192879aeeda8" />
<img width="1366" height="768" alt="Screenshot (113)" src="https://github.com/user-attachments/assets/1b6a16f7-66e8-4b63-9de9-c2c07fe5cea1" />


---

## ✨ Features

- 🛍️ **Product Catalog** — Guitars, Pianos, Drums, Violins with filter sidebars
- 🛒 **Cart System** — Add, remove, and manage cart items (MySQL-backed)
- 👤 **User Auth** — Register, login, update profile, deregister, contact us
- 💳 **Razorpay Integration** — Test-mode payment gateway on checkout
- 📬 **Contact Form** — With modal success popup
- 🔔 **Toast Notifications** — Real-time feedback on user actions
- 🔒 **Security** — SQL injection prevention via prepared statements, `.env` credential protection

---

## 🛠️ Tech Stack

-------------------------------------------------
| Layer    | Technology                          |
|----------|---------------------------- --------|
| Frontend | HTML5, CSS3, JavaScript             |
| Backend  | PHP 8.x                             |
| Database | MySQL                               |
| Server   | Apache (XAMPP)                      |
| Payments | Razorpay (Test Mode)                |
| Design   | Figma-inspired custom design system |
-------------------------------------------------

## 🎨 Design System

------------------------------------
| Token         | Value            |
--------------- |------------------|
| Header/Footer | `#121212`        |
| Background    | `#F7F5F2`        |
| Accent        | `#96281B`        |
| Heading Font  | Playfair Display |
| Body Font     | DM Sans          |
------------------------------------

## 📁 Project Structure

```
svara
├── landing.html             #homepage before login
├── register.php             # User registration
├── login.php                # User login
├── home1.html               # Homepage after login
├── guitars1.html             # Product page — Guitars
├── pianos1.html              # Product page — Pianos
├── drums1.html               # Product page — Drums
├── violins1.html             # Product page — Violins
├── contact1.html             # Contact page (logged in)
├── tutorials1.html          #Tutorial page with youtube links
├── about us1.html           # About us page
├── update.php               # Profile update
├── update.html
├── deregister.php           # Account deletion
├── de-register.html
├── cart_add.php             # Cart — add item
├── cart_get.php             # Cart — fetch items
├── cart_remove.php          # Cart — remove item
├── .env                     # Environment variables (NOT pushed to GitHub)
├── .gitignore               # Ignores .env and sensitive files
└── assets/
    ├── css/
    ├── js/
    └── images/
```

---

## ⚙️ Local Setup

### Prerequisites
- XAMPP (Apache + MySQL)
- PHP 8.x
- A browser

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/svara.git
   ```

2. **Move to XAMPP's htdocs**
   ```bash
   # Windows
   move svara C:\xampp\htdocs\svara

   # Mac/Linux
   mv svara /Applications/XAMPP/htdocs/svara
   ```

3. **Create the database**
   - Open phpMyAdmin → Create a database named `svara`
   - Import the provided SQL file (if included) or create tables manually

4. **Set up environment variables**
   - Create a `.env` file in the root directory:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=svara
   RAZORPAY_KEY_ID=your_test_key
   RAZORPAY_KEY_SECRET=your_test_secret
   ```

5. **Start XAMPP** — Apache + MySQL

6. **Open in browser**
   ```
   http://localhost/svara/index.html
   ```

---

## 🔐 Security

- All database queries use **prepared statements** (no raw SQL with user input)
- Credentials stored in `.env` (excluded from version control via `.gitignore`)
- Session-based authentication for protected pages

---

## 💳 Payment Testing

Razorpay is integrated in **test mode**. Use these test credentials:

| Field | Value |
|---|---|
| Card Number | `4111 1111 1111 1111` |
| Expiry | Any future date |
| CVV | Any 3 digits |

---

## 🙋‍♀️ Author

**Priya**
BSc IT — Somaiya University, Mumbai
[GitHub](https://github.com/your-username) • [LinkedIn](https://linkedin.com/in/your-profile)

---

## 📄 License

This project is for educational purposes.
