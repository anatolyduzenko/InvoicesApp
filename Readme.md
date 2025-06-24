# 🧾 Invoices App

**Invoices App** is a simple and modern tool for creating and managing invoices. It helps you work with many customers and accounts. You can use it for one company now, and later you can update it to support more companies if needed.

## ✨ Main Features

- **👥 Manage many customers and accounts**  
  You can add many customers and connect them to different accounts.

- **🏢 Company support**  
  Right now, the app works for one company. But it is ready for future updates to support more companies.

- **🔁 Copy invoices easily**  
  You can make a copy of any invoice. This is useful if you send the same invoice often.

- **💱 Use different currencies**  
  You can create invoices in different currencies like USD, EUR, or GEL. The app shows the correct symbol.

- **📄 Set custom templates per customer**  
  You can choose a different invoice design (template) for each customer.

## 📦 Technologies Used

- **Laravel** (backend)
- **Vue.js / Inertia.js** (frontend)
- **Tailwind CSS** (styles)
- **Puppeteer** (PDF creation) - Requires additional Docker container (https://github.com/anatolyduzenko/puppeteer-docker)
- **MySQL / Redis** (database and caching)

## 🚀 How to Start

Follow these steps to run the app on your computer:

```bash
git clone https://github.com/your-username/invoices-app.git
cd invoices-app
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

## 🛠️ Configuration

Check these lines in your `.env` file:

```env
APP_NAME=InvoicesApp
DB_CONNECTION=mysql

PUPPETEER_HOST=puppeteer
PUPPETEER_PORT=3000
```

## 📄 Invoice Templates

Invoice templates are in this folder:  
`resources/views/invoices/templates`

You can:

- Add new designs  
- Set a design for each customer  
- Test and preview the invoice before saving as PDF

## 🤝 Contribute

Do you want to help? You can make suggestions or send pull requests. All help is welcome!

## 📬 License

This project uses the [MIT license](LICENSE).
