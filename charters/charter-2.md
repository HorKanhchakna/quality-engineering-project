# Charter 2 — Resilience: Article Deletion During Favoriting

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-02 |
| **Tester** | Men Srei Tin |
| **Date** | 2026-06-06 |
| **Time Spent** | 45 minutes |

## Mission
Find how the system behaves when a user tries to favorite an article that is simultaneously deleted by its author, looking for unhandled errors or silent failures.

## Setup / Start Conditions
UserA (author) creates article 'Test Delete Article'. UserB (reader) is logged in on a separate browser and has the article open.

## Actions Performed
1. UserB navigates to the article and hovers over the favorite button.
2. UserA opens the article owner controls and clicks 'Delete Article'.
3. Within 2 seconds of deletion, UserB clicks the favorite button.
4. Observe UserB's screen for any response, error message, or console log.

## Observations / Findings
- Favorite button did nothing after the article was deleted.
- No error message was displayed to UserB — the action silently failed.
- Browser console showed no HTTP 500; the request may have returned a 404 silently.
- User experience is confusing as no feedback was given.

## Bugs Found
- **BUG-002** — No error feedback when favoriting a deleted article; silent failure with no user-facing message.
