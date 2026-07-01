# CMS Demo

A small, interview-friendly CMS demo based on the architecture of the original split CMS project. It keeps the important flows while removing production databases, internal integrations, credentials, logs, dumps, and company-sensitive logic.

## What This Demonstrates

- Token-style authentication from frontend to backend
- Centralized frontend API client with Bearer token injection
- Protected API requests with loading, success, and error states
- Document/import upload validation and local demo storage
- Filtered report query using safe mock seed data
- Optional CSV export for the report data
- API activity logging for authenticated requests
- One cached API response and a clear-cache action

## Structure

```text
cms-demo/
  README.md
  cms-demo-api/        # Dependency-light PHP API, Laravel-style route/controller structure
  cms-demo-frontend/   # Vue 3 + Vite frontend
```

## Demo Credentials

```text
Email: demo@example.test
Password: password
```

## Backend Setup

The backend is intentionally dependency-light so it can run locally without Composer or a database server. It uses JSON files in `storage/data` and local uploads in `storage/uploads`.

```bash
cd cms-demo-api
copy .env.example .env
php scripts/seed.php
php -S 127.0.0.1:8001 -t public
```

Health check:

```bash
curl http://127.0.0.1:8001/api/health
```

## Frontend Setup

```bash
cd cms-demo-frontend
copy .env.example .env
npm install
npm run dev
```

Open the Vite URL, usually `http://127.0.0.1:5173`.

## API Endpoints

Public: `GET /api/health`, `POST /api/login`.

Protected with `Authorization: Bearer <token>`:

- `GET /api/user`
- `GET /api/demo/status`
- `POST /api/uploads`
- `GET /api/reports?year=2026&category=Revenue&status=Completed`
- `GET /api/reports/export`
- `GET /api/activity-logs`
- `GET /api/cache-demo/summary`
- `POST /api/cache-demo/clear`

## Safe Data Policy

This demo uses fake users, fake report records, fake categories, and local-only runtime files. It does not include production `.env` files, production database connections, internal IP addresses or URLs, SendGrid or SFTP credentials, customer or supplier data, production logs, cache files, uploads, or database dumps.

## Interview Talking Points

- The frontend keeps API access centralized in `src/composable/useApi.ts`, matching the original project's pattern.
- The backend keeps route/controller separation and consistent JSON responses.
- Activity logging is implemented as request middleware to show auditability without exposing sensitive payloads.
- The report flow demonstrates a filterable backend query using seeded mock data.
- The cache demo shows how expensive report-style calls can be cached and cleared safely.
