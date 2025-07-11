# 🎁 GiftList

**GiftList** is a web application for creating and managing gift registries. Hosts can create lists, add products, and invite guests. Guests can view shared lists and select items, while administrators have full control over system data.

The app is built with **Laravel**, uses **Vite** + **Alpine.js** on the frontend, and runs via **Docker** for a seamless local development experience.

---

## 🚀 Getting Started

To run this project locally using Docker:

```bash
# 1. Clone the repository
git clone https://github.com/wilian-moraes/giftlist.git
cd giftlist

# 2. Create your environment file
cp .env.example .env
```

Edit the `.env` file with your local settings:

```env
APP_URL=http://localhost:8000
VITE_APP_URL=${APP_URL}

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=GiftList
DB_USERNAME=postgres
DB_PASSWORD=postgres

WWWUSER=1000
WWWGROUP=1000
```

Now run:

```bash
# 3. Build and start containers
docker-compose up --build

# 4. Install PHP dependencies
docker-compose exec app composer install

# 5. Generate Laravel app key
docker-compose exec app php artisan key:generate

# 6. Run database migrations and seeders
docker-compose exec app php artisan migrate:fresh --seed

# 7. Install Node dependencies and start Vite
docker-compose exec vite npm install
docker-compose exec vite npm run dev
```

Or if you want to run locally without Docker:

- Use the same `.env` informed on this doc
- Modify the DB data to that of your sql database
- Run the following commands on terminal:

```bash
# 1. Install Node dependencies
npm install

# 2. Run the migrations
php artisan migrate
# Or with seeds
php artisan migrate:fresh --seed

# Start Vite
npm run dev

# Start the development server
php artisan serve
```

The app will be running same at: [http://localhost:8000](http://localhost:8000)

---

### 🧪 Using Laravel Tinker

Laravel Tinker is an interactive REPL (Read–Eval–Print Loop) powered by [PsySH](https://psysh.org), allowing you to interact with your Laravel app directly from the command line.

To start Tinker:

```bash
php artisan tinker
```

You can use it for:

- Creating new records:
  ```php
  User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => bcrypt('password')]);
  ```

- Listing data:
  ```php
  Product::all();
  ```

- Updating records:
  ```php
  $user = User::find(1);
  $user->name = 'Updated Name';
  $user->save();
  ```

- Running custom logic:
  ```php
  event(new \App\Events\GiftSelected($giftId));
  ```

- Calling methods:
  ```php
  App\Models\GiftList::first()->guests;
  ```

Tinker is especially useful for:
- Testing model relationships
- Seeding specific data manually
- Debugging issues without writing test files or routes

To exit Tinker, simply type:

```bash
exit
```

---

## 📁 Environment Variables

Example `.env` variables:

```env
APP_NAME=GiftList
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=GiftList
DB_USERNAME=postgres
DB_PASSWORD=postgres

MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_URL=http://localhost:8000

WWWUSER=1000
WWWGROUP=1000
```

---

## 📦 Available Scripts (via Docker)

- `docker-compose up -d` – Start all services in detached mode  
- `docker-compose up --build -d` – Rebuild and start all services  
- `docker-compose down` – Stop and remove containers  
- `docker-compose exec app php artisan migrate` – Run Laravel migrations  
- `docker-compose exec vite npm run dev` – Start the Vite development server  
- `docker-compose exec vite npm run build` – Build frontend assets for production  

---

## 📚 Tech Stack

- [Laravel](https://laravel.com)
- [PHP-FPM](https://www.php.net/manual/en/install.fpm.php)
- [Vite](https://vitejs.dev)
- [Alpine.js](https://alpinejs.dev)
- [TailwindCSS](https://tailwindcss.com)
- [Axios](https://axios-http.com)
- [PostgreSQL](https://www.postgresql.org)
- [Docker](https://www.docker.com)

---

## 🛰️ Deployment

You can deploy this application using any provider that supports Docker and PostgreSQL, such as:

- [AWS ECS/Fargate](https://aws.amazon.com/ecs/)
- [DigitalOcean App Platform](https://www.digitalocean.com/products/app-platform)
- [Google Cloud Run](https://cloud.google.com/run)
- [Render](https://render.com)
- [Railway](https://railway.app)

For the frontend build (`npm run build`), you can optionally deploy static assets via [Netlify](https://www.netlify.com/) or [Vercel](https://vercel.com), while the Laravel backend will still require a server environment.

---

## 👤 Author

Developed by [Wilian Moraes](https://github.com/wilian-moraes)
