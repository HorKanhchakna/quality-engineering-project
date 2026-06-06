# RealWorld Example App

Full-stack Vue3 + Laravel application.

## Prerequisites
- Docker Desktop installed and running

## Quick Start

```bash
# Clone and run
git clone <repo-url>
cd quality-engineering-project
docker-compose up -d
```

Wait ~1 minute for services to start, then access:
- **Frontend**: http://localhost:4173
- **Backend API**: http://localhost:8000/api
- **Swagger UI**: http://localhost:8000/api/documentation

## Services

| Service  | Port  | Description          |
|----------|-------|---------------------|
| MySQL    | 3306  | Database (auto-created) |
| Backend  | 8000  | Laravel API         |
| Frontend | 4173  | Vue3 application    |

## Useful Commands

```bash
# View logs
docker-compose logs -f backend
docker-compose logs -f frontend

# Stop services
docker-compose down

# Reset database
docker-compose down -v && docker-compose up -d
```

## Database

Database `my_new_db` is automatically created with:
- Root password: `secret123`
- No manual setup required