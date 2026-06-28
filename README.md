# 🎵 SVARA — Musical Instruments E-Commerce Platform

A full-featured e-commerce web application for musical instruments, built with PHP, MySQL, and vanilla JavaScript.

---

## 🚀 Live Demo

> Run locally using XAMPP (see setup instructions below)

---

## 📸 Screenshots

> *(Add screenshots of your homepage, product pages, cart, and checkout here)*

---

## ✨ Features

- 🛍️ **Product Catalog** — Guitars, Pianos, Drums, Violins with filter sidebars
- 🛒 **Cart System** — Add, remove, and manage cart items (MySQL-backed)
- 👤 **User Auth** — Register, login, update profile, deregister
- 💳 **Razorpay Integration** — Test-mode payment gateway on checkout
- 📬 **Contact Form** — With modal success popup
- 🔔 **Toast Notifications** — Real-time feedback on user actions
- 🔒 **Security** — SQL injection prevention via prepared statements, `.env` credential protection

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8.x |
| Database | MySQL |
| Server | Apache (XAMPP) |
| Payments | Razorpay (Test Mode) |
| Design | Figma-inspired custom design system |

---

## 🎨 Design System

| Token | Value |
|---|---|
| Header/Footer | `#121212` |
| Background | `#F7F5F2` |
| Accent | `#96281B` |
| Heading Font | Playfair Display |
| Body Font | DM Sans |

---

## 📁 Project Structure

```
svara/
├── index.html               # Homepage
├── guitars.html             # Product page — Guitars
├── pianos.html              # Product page — Pianos
├── drums.html               # Product page — Drums
├── violins.html             # Product page — Violins
├── cart.html                # Cart page
├── checkout.php             # Checkout with Razorpay
├── contact.html             # Contact page (logged in)
├── contact_landing.php      # Contact page (pre-login)
├── register.php             # User registration
├── login.php                # User login
├── update.php               # Profile update
├── deregister.php           # Account deletion
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

**Piyu**
BSc IT — Somaiya University, Mumbai
[GitHub](https://github.com/your-username) • [LinkedIn](https://linkedin.com/in/your-profile)

---

## 📄 License

This project is for educational purposes.
