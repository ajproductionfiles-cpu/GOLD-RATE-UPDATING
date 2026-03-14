# Gold Rate Display API

Node.js + Express API with JWT authentication, role-based access (`SUPER_ADMIN`, `USER`), gold rate CRUD, location-based rates, display-code public endpoint, and Razorpay subscription flow.

## Tech Stack
- Node.js + Express
- MySQL
- Sequelize ORM
- JWT auth
- Razorpay subscriptions

## Folder Structure

```bash
src/
  app.js
  server.js
  config/
  controllers/
  middlewares/
  models/
  routes/
  services/
  utils/
```

## Setup

1. Install dependencies:
   ```bash
   npm install
   ```
2. Copy env:
   ```bash
   cp .env.example .env
   ```
3. Update environment variables.
4. Run app:
   ```bash
   npm run dev
   ```

## Default Super Admin
- Email: `superadmin@goldrate.com`
- Password: `Admin@123`

## API Base URL
`/api/v1`

## Main Endpoints

### Auth
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`

### Locations (Super Admin)
- `POST /api/v1/locations`
- `GET /api/v1/locations`
- `PUT /api/v1/locations/:id`
- `DELETE /api/v1/locations/:id`

### Gold Rates
- `POST /api/v1/gold-rates` (Super Admin)
- `GET /api/v1/gold-rates` (Authenticated users)
- `GET /api/v1/gold-rates/:id` (Authenticated users)
- `PUT /api/v1/gold-rates/:id` (Super Admin)
- `DELETE /api/v1/gold-rates/:id` (Super Admin)

### Public Display API
- `GET /api/v1/public/rate/:displayCode`

### Subscriptions
- `POST /api/v1/subscriptions/create` (Authenticated)
- `POST /api/v1/subscriptions/activate` (Authenticated, usually from post-payment logic)

## Notes
- `displayCode` can be used by TV displays/widgets to fetch current location rates publicly.
- Razorpay keys and plan id are required for subscription creation.
