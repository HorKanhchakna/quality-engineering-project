# Charter 1 — Concurrency: Favorite Count Integrity

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-01 |
| **Tester** | Phearom Ratha |
| **Date** | 2026-06-06 |
| **Time Spent** | 60 minutes |

## Mission
Explore what happens when multiple users favorite the same article simultaneously, hunting for lost updates, incorrect counts, or race conditions.

## Setup / Start Conditions
One article 'Race Article' published. Three user accounts (user1, user2, user3) each logged into separate Incognito browser windows.

## Actions Performed
1. All three users navigate to the same article simultaneously.
2. User1 clicks favorite → UI shows count = 1.
3. User2 clicks favorite immediately after → User2 sees count = 2, but User1's screen still shows count = 1 (no reload).
4. User3 clicks favorite → similar delay.
5. All users manually refresh page.
6. After refresh, all users see the correct final count.

## Observations / Findings
- Immediate counts were inconsistent across users (stale client state).
- One user saw count=1 while another saw count=2 at the same moment.
- After manual refresh all counts corrected.
- No data loss observed in the database — the bug is purely a UI real-time sync issue.

## Bugs Found
- **BUG-001** — Favorite count not updated in real-time across clients; race condition causes stale UI state.
