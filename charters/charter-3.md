# Charter 3 — Data Integrity: Editing While Another User Favorites

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-03 |
| **Tester** | Hor Kanhchakna |
| **Date** | 2026-06-06 |
| **Time Spent** | 30 minutes |

## Mission
Detect data mismatch or UI inconsistency when an article is edited while another user simultaneously favorites it.

## Setup / Start Conditions
UserA (author) owns article 'Original Title' with 0 favorites. UserB is logged in on a separate browser with the article open.

## Actions Performed
1. UserB opens the article and is ready to click favorite.
2. UserA clicks 'Edit Article', changes title to 'Updated Title', and clicks Publish.
3. Simultaneously, UserB clicks the favorite button.
4. Observe title displayed on UserB's screen without refreshing.
5. Manually refresh UserB's page and observe again.

## Observations / Findings
- Favorite count incremented correctly (0→1) on both screens after action.
- However, UserB's screen still showed 'Original Title' instead of 'Updated Title' without a page refresh.
- After manual refresh, correct title appeared.
- No data corruption — counts were consistent in the database.
- This is a real-time UI sync limitation.

## Bugs Found
- **BUG-003** — Article title does not update in real-time for other users after edit; requires manual page refresh.
