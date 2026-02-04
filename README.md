[tirreno](https://www.tirreno.com) is an open-source security framework.

## Quick start

**Run:**

```bash
curl -sL tirreno.com/t.yml | docker compose -f - up -d
```

## Persistent Docker deploy

You can run the tirreno container with volume to keep persistent data in the following way:

**1. Create network:**

```bash
docker network create tirreno-network
```

**2. Add PostgreSQL:**

```bash
docker run -d --name tirreno-db --network tirreno-network -e POSTGRES_DB=tirreno -e POSTGRES_USER=tirreno -e POSTGRES_PASSWORD=secret -v ./db:/var/lib/postgresql/data postgres:15
```

**3. Add tirreno:**

```bash
docker run --name tirreno-app --network tirreno-network -p 8585:80 -v tirreno:/var/www/html -d tirreno
```

## Install via docker-compose

Check tirreno [`Administrator documentation`](https://github.com/tirrenotechnologies/ADMIN.md?tab=readme-ov-file#docker-installation)
