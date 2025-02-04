# Finance Popularity Tool

## Overview

This application crawls finance-related tweets from specified Twitter handles, processes them to extract financial instruments (e.g., `$BTC`, `$EURUSD`), and generates analytics on the most mentioned instruments (daily, weekly, monthly). It is built with:

- **Laravel (PHP Framework)** for backend logic and API endpoints
- **MySQL** for persistent storage
- **Redis** for caching and queuing
- **Docker & Docker Compose** to containerize and orchestrate services

**Key Features:**
- Periodic crawling of tweets from predefined handles
- Processing crawled tweets to extract instruments
- Storing mentions and computing aggregated stats (daily, weekly, monthly)
- Providing RESTful endpoints for CRUD operations on handles, instruments, tweets, and analytics

---

## Project Structure

**Key Directories:**
- `app/Models/`: Eloquent models (`Handle`, `Tweet`, `Instrument`, `InstrumentMention`, `Stat`)
- `app/Http/Controllers/`: Controllers for CRUD endpoints and stats
- `app/Jobs/`: Queue jobs for crawling, processing tweets, and updating stats
- `app/Console/Commands/`: Artisan commands for dispatching crawl, process, and stats-update jobs
- `database/migrations/`: Table definitions for all database entities
- `database/seeders/`: Seeders to populate initial data
- `tests/`: Contains `Feature` and `Unit` tests

---

## Technology Stack

- **Laravel:** Framework for routing, Eloquent ORM, migrations, queues, and caching
- **MySQL:** Relational database for primary storage
- **Redis:** For caching frequently accessed data and as a queue driver
- **Docker & Docker Compose:** For containerization and service orchestration
- **Composer:** PHP dependency management
- **PHPUnit:** Automated testing framework

---

## Database Schema & Models

**Entities:**

- **handles**: Stores Twitter handles and crawl frequency
- **tweets**: Stores tweets linked to handles
- **instruments**: Stores financial instrument symbols
- **instrument_mentions**: Links instruments to tweets with timestamps
- **stats**: Precomputed aggregates (daily, weekly, monthly mentions)

**Relationships:**

- **Handle** → hasMany → Tweet
- **Tweet** → belongsTo → Handle
- **Tweet** → belongsToMany → Instrument (through InstrumentMention)
- **Instrument** → hasMany → InstrumentMention
- **Instrument** → hasOne → Stat

---

## Core Functionality

**Crawling Tweets**
- **CrawlTweetsJob**: Fetch tweets for each handle.
- **DispatchCrawlJobs** Command (php artisan crawl:tweets): Dispatches crawl jobs.

**Processing Tweets**
- **ProcessTweetsJob**: Extracts instruments from unprocessed tweets.
- **ProcessTweetsCommand** (php artisan tweets:process): Dispatches jobs to process tweets.

**Stats Computation**
- **UpdateStatsJob**: Aggregates mentions and updates the stats table.
- **UpdateStatsCommand** (php artisan stats:update): Dispatches update stats job.

**These can be scheduled to run periodically via schedule:work.**

---

## Analytics Endpoints

- **GET** /api/top-instruments/daily
- **GET** /api/top-instruments/weekly
- **GET** /api/top-instruments/monthly

Returns top 10 instruments for the given period, cached for performance.

---

## RESTful API Endpoints

**Handles (/api/handles):**

- **GET** /handles
- **POST** /handles
- **GET** /handles/{id}
- **PUT** /handles/{id}
- **DELETE** /handles/{id}


**Instruments (/api/instruments):**

- **GET** /instruments
- **POST** /instruments
- **GET** /instruments/{id}
- **PUT** /instruments/{id}
- **DELETE** /instruments/{id}


**Tweets (/api/tweets):**

- **GET** /tweets
- **POST** /tweets
- **GET** /tweets/{id}
- **PUT** /tweets/{id}
- **DELETE** /tweets/{id}

---


## Queuing & Scheduling

- **Queue:** Uses Redis. php artisan queue:work redis to process queued jobs.

- **Scheduler:** php artisan schedule:work runs tasks.

- In Docker, queue-worker and scheduler services run these continuously.

---

## Caching

- Uses Redis to cache expensive queries (e.g., top instruments). 
- Cache::remember() is used in StatsController.

---

## Testing

**Manual Testing:**

- Used Postman or curl for CRUD endpoints.
- Run php artisan crawl:tweets, php artisan tweets:process, php artisan stats:update and check DB.


**Automated Tests:**

- **Feature Tests:** Test API endpoints.
- **Unit Tests:** Test logic like instrument extraction, stat calculations.

Factories and seeders assist in setting up test data.

---

## Getting Started

### Prerequisites
- Docker & Docker Compose
- Composer (optional if not running Composer commands locally)

### Installation Steps
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/anastasis-georgiou/finance-popularity-tool.git
   cd finance-popularity-tool
2. **Set Environment Variables:**
    ```bash
    cp .env.example .env
3. **Build and start containers**
   ```bash
    docker-compose build
    docker-compose up -d
4. **Run migrations and seeders**
   ```bash
    docker exec -it laravel-app php artisan migrate --seed
5. **Check Accessibility: Open http://localhost:8000**

## Troubleshooting

- **DB Connection Issues:** Check .env and docker-compose.yml.
- **Redis Issues:** Ensure REDIS\_HOST=redis and container is running.
- **Queue Issues:** Make sure queue-worker service is up and processing jobs.
- **Stats Not Updating:** Check scheduler service and logs.
- **Scraping Tweets:** Adjust scraping logic if needed. Consider headless browser for dynamic content.


## Future Improvements

- Add authentication to protect certain endpoints.
- More robust error handling, rate limiting.
- A frontend UI for analytics.
- Advanced scraping with headless browsers for real tweets.
