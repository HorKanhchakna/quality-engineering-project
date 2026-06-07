# Phase 3: Infrastructure Analysis — Performance Report

**Project:** Conduit (RealWorld) — Path A: The Integrator
**Tester:** Phearom Ratha
**Date:** June 2026
**Tools:** K6 / JMeter
**Scripts:** `performance/load-test.js` / `performance/load-test.jmx`

---

## Objective
Determine the "Breaking Point" of the Conduit application and analyze its behavior under pressure by establishing a baseline and incrementally scaling load.

## Methodology
1. Establish a 1-user baseline to capture normal response times.
2. Scale load incrementally: 10 → 25 → 50 → 75 → 100 concurrent users.
3. Monitor response times, throughput, and error rates at each level.
4. Identify the breaking point and primary bottleneck.

---

## Performance Metrics Summary

| Concurrent Users | Avg Response Time | P95 Response Time | Throughput (req/s) | Error Rate |
|------------------|-------------------|-------------------|--------------------|------------|
| 1 (Baseline) | 45 ms | 52 ms | 22 req/s | 0% |
| 10 | 85 ms | 112 ms | 117 req/s | 0% |
| 25 | 195 ms | 245 ms | 128 req/s | 0% |
| 50 | 420 ms | 580 ms | 119 req/s | 2% |
| 75 | 890 ms | 1,120 ms | 84 req/s | 15% |
| 100 (FAILURE) | 1,850 ms | 2,400 ms | 54 req/s | 45% |

---

## Response Time vs. User Load Graph

```
Response Time (ms) vs. Concurrent Users

2400 |                                                       ●
1850 |                                                  ●    |  45% errors
1120 |                                            ●          |  15% errors
 580 |                                  ●                    |   2% errors
 245 |                       ●                               |
 112 |            ●                                          |
  52 |  ●                                                    |
     +-----+----------+----------+----------+----------+---->
      1     10         25         50         75        100
                           Concurrent Users
```

---

## Bottleneck Analysis

| Metric | Details |
|--------|--------|
| **Breaking Point** | ~75–100 concurrent users |
| **Primary Bottleneck** | Database connection pool exhaustion — PostgreSQL begins queuing queries beyond 75 concurrent connections, causing cascading timeouts. |
| **Secondary Bottleneck** | Backend CPU spike observed at 100 users due to synchronous JWT token verification on each request without caching. |
| **Safe Operating Zone** | Up to 50 concurrent users — avg response time stays under 500ms and error rate is below 2%. |

---

## Detailed Observations

### 1–25 Users (Stable Zone)
- Response times remain under 250ms.
- Throughput scales linearly from 22 to 128 req/s.
- No errors observed. System operates well within capacity.

### 50 Users (Warning Zone)
- Average response time exceeds 400ms.
- First errors appear (2% error rate).
- Throughput begins to plateau and slightly decline (119 req/s vs. 128 at 25 users).
- Database connection pool nearing capacity.

### 75 Users (Degraded Performance)
- P95 response time exceeds 1 second.
- Error rate jumps to 15%.
- Throughput drops significantly to 84 req/s.
- PostgreSQL query queue observed in database logs.

### 100 Users (System Failure)
- Average response time approaches 2 seconds.
- 45% of requests fail.
- Throughput drops to 54 req/s (less than half of peak).
- Database connection timeouts dominate error logs.
- Backend CPU utilization exceeds 90%.

---

## Recommended Fixes

| Priority | Recommendation | Expected Impact |
|----------|---------------|----------------|
| P1 | Increase database connection pool size from default to 50+ | Delay breaking point to ~150 users |
| P1 | Implement PgBouncer for connection pooling | Reduce connection overhead by 60% |
| P2 | Cache JWT verification results with short TTL (30s) | Reduce CPU load per request by ~20% |
| P3 | Add Redis-based response caching for read-heavy endpoints | Improve throughput for GET /articles |

---

## Conclusion
The Conduit application can safely handle up to **50 concurrent users** in its current configuration. Beyond this threshold, database connection pool exhaustion becomes the primary bottleneck, leading to cascading failures at 100 users. Implementing connection pooling (PgBouncer) and increasing the pool size would be the highest-impact improvements.
