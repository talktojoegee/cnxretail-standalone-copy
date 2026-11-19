# CNX Retail API v2

Base URL: `/api/v2`

Legacy merchant mobile API remains at `/api/*` (unchanged). Use v2 for Next.js landing, marketplace, merchant dashboard, and admin (tunnel).

## Envelope

```json
{
  "success": true,
  "message": "OK",
  "data": {},
  "meta": {},
  "errors": null
}
```

## Auth

| Audience | Login | Header | JWT claim `typ` |
|----------|-------|--------|-----------------|
| Merchant | `POST /api/v2/auth/login` | `Authorization: Bearer <token>` | `merchant` |
| Admin | `POST /api/v2/admin/auth/login` | `Authorization: Bearer <token>` | `admin` |

Merchant register: `POST /api/v2/auth/register`  
Merchant me / logout / refresh: `GET /auth/me`, `POST /auth/logout`, `POST /auth/refresh`  
Admin me / logout: `GET /admin/auth/me`, `POST /admin/auth/logout`

## CORS

Set `FRONTEND_URL` in `.env` to a comma-separated list of Next.js origins (e.g. `http://localhost:3000`). Bearer tokens do not need credentialed cookies.

## Guest cart

Public cart/checkout use header `X-Cart-Token` (UUID). If omitted, the API issues a new token and returns it in the `X-Cart-Token` response header and in `data.token`. Persist it in the Next.js client (localStorage / cookie).

## Public (landing + marketplace)

| Method | Path |
|--------|------|
| GET | `/public/home` |
| GET | `/public/marketplace` |
| GET | `/public/marketplace/facets` |
| GET | `/public/products/{slug}` |
| GET | `/public/stores/{slug}` |
| GET | `/public/categories/{slug}` |
| GET | `/public/search` |
| GET/POST/PATCH/DELETE | `/public/cart`, `/public/cart/items` |
| GET/POST | `/public/checkout` |
| POST | `/public/contact` |
| POST | `/public/buyer-requests` |

Query filters for catalog: `keyword`, `category`, `vendor`, `sort` (`newest|price_asc|price_desc|name`), `per_page`, `page`.

## Merchant

Requires merchant JWT.

| Method | Path |
|--------|------|
| GET | `/merchant/dashboard` |
| GET/POST | `/merchant/items` |
| GET | `/merchant/items/{id}` |
| GET/POST | `/merchant/categories` |
| GET/PATCH | `/merchant/settings/tenant` |

Remaining merchant modules (invoices, bills, etc.) stay on v1 `/api/auth/*` until ported.

## Admin (tunnel)

Requires admin JWT.

| Method | Path |
|--------|------|
| GET | `/admin/dashboard` |
| GET | `/admin/tenants` |
| GET | `/admin/tenants/{slug}` |
| PATCH | `/admin/tenants/{slug}/status` `{ "status": 0\|1 }` |
| POST | `/admin/tenants/{slug}/extend` `{ "days": 7\|14\|30\|90\|365 }` |
| GET | `/admin/subscriptions` |
| GET | `/admin/buyer-requests` |
| GET | `/admin/contact-inquiries` |
| GET | `/admin/activity-logs` |
| GET/POST | `/admin/pricing` |
| PATCH | `/admin/pricing/{id}` |
| GET/POST | `/admin/daily-motivations` (`time`: `1` morning, `3` evening) |
| PATCH | `/admin/daily-motivations/{id}` |
| GET/POST | `/admin/users` |
| GET | `/admin/notifications` |

List endpoints accept `q`, date `from`/`to`, `status`, `per_page`, `page` where applicable. Pagination lives in `meta` (`from` is the first serial for the page).

## Example (curl)

```bash
# Home
curl -s http://127.0.0.1:8000/api/v2/public/home | jq

# Cart
TOKEN=$(curl -si http://127.0.0.1:8000/api/v2/public/cart | awk -F': ' '/^X-Cart-Token/ {print $2}' | tr -d '\r')
curl -s -H "X-Cart-Token: $TOKEN" -H "Content-Type: application/json" \
  -d '{"item":1,"qty":1}' http://127.0.0.1:8000/api/v2/public/cart/items | jq

# Merchant login
curl -s -H "Content-Type: application/json" \
  -d '{"email":"demo@cnxretail.com","password":"password123"}' \
  http://127.0.0.1:8000/api/v2/auth/login | jq

# Admin login
curl -s -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"..."}' \
  http://127.0.0.1:8000/api/v2/admin/auth/login | jq
```
