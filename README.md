# China Metro Restaurant

China Metro Restaurant is a PHP + MySQL restaurant website built for XAMPP. It includes a customer-facing website, an admin panel, customer account registration/login, profile management, menu browsing, offers, restaurant information, contact enquiries, and Google Maps location support.

## Tech Stack

- PHP
- MySQL / MariaDB
- HTML
- CSS
- JavaScript
- XAMPP

## GitHub Profile

https://github.com/anolz12/china_metro

## Jira Profile

https://anolalwyndsouza-chinametro.atlassian.net/jira/software/projects/SCRUM/boards/1/backlog

## Features

### Public Website

- Homepage with branding and restaurant information
- Full menu page using MySQL data
- Offers page
- Contact page with enquiry form
- Embedded Google Maps location
- Customer registration
- Customer login/logout
- Customer profile page

### Customer Account

- Register with Full Name, Email, Password, Confirm Password
- Server-side validation
- Duplicate email protection
- Password hashing with `password_hash()`
- Login with `password_verify()`
- Profile page for updating name and phone number
- Password change section with current password verification

### Admin Panel

- Admin login
- Dashboard overview
- Manage menu items
- Manage offers
- Manage restaurant information
- View contact submissions

## Project Structure

- `index.php` - homepage
- `menu.php` - customer menu page
- `offers.php` - offers page
- `contact.php` - contact page with Google Maps
- `register.php` - customer registration page
- `login.php` - customer login page
- `logout.php` - customer logout
- `profile.php` - customer profile page
- `admin/` - admin dashboard and management pages
- `includes/` - shared PHP config, auth, layout, and data helpers
- `assets/` - CSS, JS, images
- `database/schema.sql` - database schema
- `database/migrate.php` - migration / seed script
- `data/` - backup JSON seed data used during migration

## Database

Database name:

`china_metro_restaurant`

Main tables:

- `site_settings`
- `admins`
- `users`
- `menu_items`
- `offers`
- `contacts`

## Default Credentials

### Admin Login

- Username: `admin`
- Password: `chinametro123`

## Local Setup (XAMPP)

1. Copy the project into:
   `C:\xampp\htdocs\ChinaMetroRestaurant`
2. Start `Apache` and `MySQL` from XAMPP.
3. Make sure the database connection settings in `includes/config.php` match your local MySQL setup.
4. Run the migration script to create the schema and seed the project data:

```powershell
C:\xampp\php\php.exe c:\xampp\htdocs\ChinaMetroRestaurant\database\migrate.php
```

5. Open the website in your browser:

```text
http://localhost/ChinaMetroRestaurant/
```

## Database Config

Default config in `includes/config.php`:

- Host: `127.0.0.1`
- Port: `3306`
- Database: `china_metro_restaurant`
- User: `root`
- Password: empty by default

You can also use environment variables:

- `CHINA_METRO_DB_HOST`
- `CHINA_METRO_DB_PORT`
- `CHINA_METRO_DB_NAME`
- `CHINA_METRO_DB_USER`
- `CHINA_METRO_DB_PASS`

## Customer Flow

1. Customer opens `Register`
2. Account is created in the `users` table
3. Password is hashed before saving
4. Customer is redirected to `Login`
5. After login, customer can open `Profile`
6. Customer can update name, phone, and password

## Contact & Maps

The contact page includes:

- Contact enquiry form saved into MySQL
- Restaurant address, phone, and email
- Embedded Google Maps iframe
- Direct Google Maps location button

## Notes

- The `data/` folder is kept as backup seed data.
- The live app uses MySQL, not JSON, for runtime data.
- If MySQL is not running, customer/admin/database-backed pages will not work.
- If you change the database manually, you may want to re-run `database/migrate.php` only when you are okay reseeding data from the backup JSON files.

## Future Improvements

- Customer order history
- Checkout and ordering flow
- Email notifications
- Password reset flow
- Customer dashboard improvements
- Image uploads for menu items

