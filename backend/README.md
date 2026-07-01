# WebShopX Backend

Backend API สำหรับระบบ e-commerce

## Installation

```bash
cd backend
npm install
```

## Configuration

1. Copy `.env.example` to `.env`
2. Update environment variables with your settings

```bash
cp .env.example .env
```

## Running

**Development:**
```bash
npm run dev
```

**Production:**
```bash
npm start
```

## API Documentation

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/refresh` - Refresh JWT token
- `POST /api/auth/logout` - Logout user

### Users
- `GET /api/users/profile` - Get user profile
- `PUT /api/users/profile` - Update profile
- `PUT /api/users/password` - Change password
- `POST /api/users/deposit` - Add balance (Admin only)
- `GET /api/users` - List users (Admin only)
- `PUT /api/users/:id` - Update user (Admin only)
- `DELETE /api/users/:id` - Delete user (Admin only)

### Products
- `GET /api/products` - Get all products
- `GET /api/products/:id` - Get product details
- `POST /api/products` - Create product (Admin only)
- `PUT /api/products/:id` - Update product (Admin only)
- `DELETE /api/products/:id` - Delete product (Admin only)
- `PUT /api/products/:id/stock` - Update stock (Admin only)

### Orders
- `POST /api/orders` - Create order
- `GET /api/orders` - Get user orders
- `GET /api/orders/:id` - Get order details
- `GET /api/orders` - Get all orders (Admin only)

### Payments
- `POST /api/payments/bank-slip` - Bank slip payment
- `POST /api/payments/truewallet` - True Wallet payment
- `POST /api/payments/webhook` - Payment webhook

### Admin
- `GET /api/admin/dashboard` - Dashboard data
- `GET /api/admin/sales/monthly` - Monthly sales
- `GET /api/admin/sales/yearly` - Yearly sales
- `PUT /api/admin/config` - Update site config (Name, Colors)
- `GET /api/admin/config` - Get site config

### Configuration
- `GET /api/config` - Get public config
