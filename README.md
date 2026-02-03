[tirreno](https://www.tirreno.com) is an open-source security framework.

tirreno *[tir.ˈrɛ.no]* helps understand, monitor, and protect your product from threats, fraud, and abuse. While classic cybersecurity focuses on infrastructure and network perimeter, most breaches occur through compromised accounts and application logic abuse that bypasses firewalls, SIEM, WAFs, and other defenses. tirreno detects threats where they actually happen: inside your product.

tirreno is a few-dependency, "low-tech" PHP/PostgreSQL application. After a straightforward five-minute installation, you can ingest events through API calls and immediately access a real-time threat dashboard.

## Built for

* **Self-hosted, internal and legacy apps**: Embed security layer
  to extend your security through audit trails, protect user accounts
  from takeover, detect cyber threats and monitor insider threats.
* **SaaS and digital platforms**: Prevent cross-tenant data leakage,
  online fraud, privilege escalation, data exfiltration and business
  logic abuse.
* **Mission critical applications**: Sensitive application protection,
  even in air-gapped deployments.
* **Industrial control systems (ICS) and command & control (C2)**: Protect,
  operational technology, command systems, and critical infrastructure
  platforms from unauthorized access and malicious commands.
* **Non-human identities (NHIs)**: Monitor service accounts, API keys,
  bot behaviors, and detect compromised machine identities.
* **API-first applications**: Protect against abuse, rate limiting
  bypasses, scraping, and unauthorized access.

## Application types

* **Self-hosted, internal and legacy apps**: Embed security layer
  to extend your security through audit trails, protect user accounts
  from takeover, detect cyber threats and monitor insider threats.
* **SaaS and digital platforms**: Prevent cross-tenant data leakage,
  online fraud, privilege escalation, data exfiltration and business
  logic abuse.
* **Mission critical applications**: Sensitive application protection,
  even in air-gapped deployments.
* **Industrial control systems (ICS) and command & control (C2)**: Protect,
  operational technology, command systems, and critical infrastructure
  platforms from unauthorized access and malicious commands.
* **Non-human identities (NHIs)**: Monitor service accounts, API keys,
  bot behaviors, and detect compromised machine identities.
* **API-first applications**: Protect against abuse, rate limiting
  bypasses, scraping, and unauthorized access.

## How to use this image

You can run the tirreno container with volume to keep persistent data in the following way:

```bash
docker run --name tirreno-app --network tirreno-network -p 8585:80 -v tirreno:/var/www/html -d tirreno
```

This assumes you've already launched a docker network and a suitable PostgreSQL database container on this network.
You may raise network and a database container with volume like this:

```bash
docker network create tirreno-network
docker run -d --name tirreno-db --network tirreno-network -e POSTGRES_DB=tirreno -e POSTGRES_USER=tirreno -e POSTGRES_PASSWORD=secret -v ./db:/var/lib/postgresql/data postgres:15
```

## tirreno installation

Access the app via http://localhost:8585/install/ or http://host-ip:8585/install/ in a browser
and fill up the form with variables that you have used for db credentials:

```
Database URL:        postgres://tirreno:secret@tirreno-db:5432/tirreno
```

or

```
Database username:   tirreno
Database password:   secret
Database host:       tirreno-db
Database port:       5432
Database name:       tirreno
Admin email:         <email-for-notifications>
```

Redirect on http://localhost:8585/signup or http://host-ip:8585/signup.

## Install via [`docker-compose`](https://github.com/docker/compose)

Example docker-compose.yml for `tirreno`:

```
# tirreno with PostgreSQL
#
# Access via http://localhost:8585/install/ or http://host-ip:8585/install/
#
# During initial tirreno setup,
#
# Database URL:          postgres://tirreno:secret@tirreno-db:5432/tirreno
#
# or
#
# Database username:     tirreno
# Database password:     secret
# Database host:         tirreno-db
# Database port:         5432
# Database name:         tirreno
# Admin email:           <email-for-notifications>

services:

    tirreno-app:
        image: tirreno/tirreno:latest
        restart: always
        ports:
            - 8585:80
        networks:
           - tirreno-network
        volumes:
            - tirreno-volume:/var/www/html/config/local
        environment:
            - DATABASE_URL=postgres://tirreno:${POSTGRES_PASSWORD:-secret}@tirreno-db:5432/tirreno

    tirreno-db:
        image: postgres:15
        restart: always
        environment:
            - POSTGRES_USER=tirreno
            - POSTGRES_PASSWORD=secret
            - POSTGRES_DB=tirreno
        networks:
            - tirreno-network
        volumes:
            - ./db:/var/lib/postgresql/data

networks:
    tirreno-network:
        driver: bridge

volumes:
    tirreno-volume:
```
