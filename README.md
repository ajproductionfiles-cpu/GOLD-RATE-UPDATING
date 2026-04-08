# Jewellery Ads Portal (PHP)

A lightweight PHP + SQLite portal for managing jewellery products used in ads.

## Features
- User login system with admin/user roles.
- User registration with profile photo (camera supported on mobile).
- Add jewellery with:
  - category
  - karat
  - details
  - user photo upload from camera/gallery
- Category management (admin).
- Gallery view for all uploads.
- Portal settings (admin): portal name and registration control.
- Password reset flow (token based demo flow).
- Click-and-search similar jewellery using image hashing (requires GD extension).

## Quick Start
```bash
php -S localhost:8000
```
Then open `http://localhost:8000`.

### Default admin
- Email: `admin@portal.local`
- Password: `Admin@123`

## Notes
- Database auto-created at `storage/portal.sqlite`.
- Uploads stored in `public/uploads/`.
- For production, configure HTTPS, CSRF protection, and email delivery for reset links.
