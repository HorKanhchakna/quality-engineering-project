# Bug Tracking Log

**Project Name:** Conduit (RealWorld) — Path A: The Integrator
**Reporters:** Hor Kanhchakna, Poeun Sreytey, Phearom Ratha, Men Srei Tin
**Date:** June 2026

---

## Summary

| Bug ID | Title | Severity | Priority | Found In | Status |
|--------|-------|----------|----------|----------|--------|
| BUG-001 | Favorite count inconsistent during concurrent favorites | Major | P2 | SBTM-01 | Open |
| BUG-002 | No error feedback when favoriting deleted article | Minor | P3 | SBTM-02 | Open |
| BUG-003 | Article title not updated in real-time after edit | Minor | P3 | SBTM-03 | Open |
| BUG-004 | No maximum length validation on article titles | Minor | P3 | SBTM-05 | Open |

---

## BUG-001 — Favorite Count Inconsistent During Concurrent Favorites

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-001 |
| **Bug Title** | Favorite count inconsistent across users during concurrent favorites |
| **Severity** | Major — Main feature broken |
| **Priority** | P2 — Fix before next release |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. Create article 'Race Article'. 2. Log in as three different users in separate Incognito windows. 3. All three navigate to the same article. 4. Click favorite simultaneously (or within 1–2 seconds of each other). 5. Observe favorite count on each screen WITHOUT refreshing. |
| **Expected Result** | All users should see the same updated count (e.g., 3) immediately after all favorites are submitted. |
| **Actual Result** | User1 sees count=1. User2 sees count=2. User1 still sees count=1. Counts are inconsistent across clients until manual refresh. |
| **Evidence** | Side-by-side browser windows showing different counts. |
| **Status** | Open |

---

## BUG-002 — No Error Feedback When Favoriting a Deleted Article

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-002 |
| **Bug Title** | No error feedback when favoriting a deleted article |
| **Severity** | Minor — UI feedback missing |
| **Priority** | P3 — Low priority |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. UserA creates an article. 2. UserB opens the article in a separate browser window. 3. UserA deletes the article. 4. Within 2 seconds, UserB clicks the favorite (heart) button. |
| **Expected Result** | A user-friendly error message such as 'Article not found' or 'Cannot favorite a deleted article' should be displayed. |
| **Actual Result** | Favorite button does nothing. No error message shown. No console error visible. Favorite count stays at 0. |
| **Evidence** | Screen recording: button click with zero response. |
| **Status** | Open |

---

## BUG-003 — Article Title Not Updated in Real-Time After Edit

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-003 |
| **Bug Title** | Article title not updated in real-time for other users after edit |
| **Severity** | Minor — UI inconsistency |
| **Priority** | P3 — Low priority |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. UserA creates article 'Original Title'. 2. UserB opens the same article in a separate browser. 3. UserA edits the title to 'Updated Title' and saves. 4. UserB observes the article title WITHOUT refreshing the page. |
| **Expected Result** | The title should update in real-time (or at minimum, a notification should alert the user that the article has changed). |
| **Actual Result** | UserB still sees 'Original Title' until they manually refresh the page. After refresh, correct title appears. |
| **Evidence** | Screenshot: UserA shows 'Updated Title', UserB simultaneously shows 'Original Title'. |
| **Status** | Open |

---

## BUG-004 — No Maximum Length Validation on Article Titles

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-004 |
| **Bug Title** | No maximum length validation on article titles |
| **Severity** | Minor — UI rendering issue |
| **Priority** | P3 — Low priority |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. Log in as any user. 2. Navigate to New Post (/editor). 3. Enter a title with 500+ characters (e.g., repeat 'A' 500 times). 4. Fill in description and body with valid data. 5. Click 'Publish Article'. 6. Navigate to the home page and observe the article card. |
| **Expected Result** | The system should enforce a maximum title length (e.g., 255 characters) and display a validation error if exceeded. |
| **Actual Result** | Article is created successfully with a 500+ character title. The title overflows the article card on the home feed, breaking the UI layout. |
| **Evidence** | Screenshot: article card with overflowing title breaking feed layout. |
| **Status** | Open |
