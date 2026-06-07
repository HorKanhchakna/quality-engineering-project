# Presentation Slides Outline

**Project:** Conduit (RealWorld) — Path A: The Integrator
**Team:** Phearom Ratha, Men Srei Tin, Hor Kanhchakna, Poeun Sreytey
**Duration:** 10 Minutes

---

## Slide 1: Title & Introduction
- **Project:** Conduit (RealWorld) — Path A: The Integrator
- **Tech Stack:** Vue3 Frontend + Laravel Backend + MySQL Database
- **Team Members:**
  - Phearom Ratha
  - Men Srei Tin
  - Hor Kanhchakna
  - Poeun Sreytey

---

## Slide 2: Strategy & Modeling
- **Application Map:** User Authentication → Article CRUD → Social Features (Favorites, Comments, Follows)
- **Heuristics Used:**
  - Equivalence Partitioning for input domains
  - Boundary Value Analysis for limits
  - Error Guessing for edge cases
  - Role-Based Testing for authorization
  - SBTM for investigative risk hunting
- **5 Charter Missions:**
  1. Concurrency (Favorite Count Integrity)
  2. Resilience (Deletion During Favoriting)
  3. Data Integrity (Edit During Favorite)
  4. Security (Authentication Token Edge Cases)
  5. Robustness (Extreme Input Boundaries)

---

## Slide 3: Investigative Findings (Show, Don't Tell)
- **Bug Highlight 1 — BUG-001:** Favorite count race condition
  - Show: Side-by-side browser screenshot with different counts
  - Root Cause: No real-time sync (WebSocket/SSE) between clients
- **Bug Highlight 2 — BUG-002:** Silent failure when favoriting deleted article
  - Show: Screen recording of button click with no response
  - Root Cause: Frontend doesn't handle 404 response from deleted article

---

## Slide 4: Engineering Depth
- **White-Box Testing:**
  - JWT module tested with PHPUnit (4 test files)
  - Coverage: Token parsing, building, generation, signature verification
- **API Validation:**
  - Postman collection: 13+ requests with full CRUD
  - Live demo: Run collection showing all green assertions
- **UI Automation:**
  - Playwright: auth.spec.ts + article.spec.ts
  - 2 E2E user journeys automated

---

## Slide 5: Performance Limits
- **Show:** Response Time vs. Load graph
- **Breaking Point:** 75–100 concurrent users
- **Bottleneck:** Database connection pool exhaustion (PostgreSQL)
- **Safe Zone:** ≤50 users (avg RT < 500ms, error rate < 2%)
- **Fix:** PgBouncer + increased pool size

---

## Demo Plan (After Slides)
1. **Unit Tests:** Run PHPUnit, show test results
2. **Postman Collection:** Execute full CRUD flow, show assertions passing
3. **UI Automation:** Run Playwright specs, show browser automation
4. **Performance Test:** Run load test, show response time scaling
