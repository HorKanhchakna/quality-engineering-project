# Charter 5 — Robustness: Extreme Input Boundary Testing

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-05 |
| **Tester** | Men Srei Tin |
| **Date** | 2026-06-06 |
| **Time Spent** | 35 minutes |

## Mission
Test the application's resilience against extreme and unusual inputs — very long strings, special characters, Unicode, empty fields, and script injection attempts — to identify input validation gaps or UI rendering issues.

## Setup / Start Conditions
User logged in as testuser. New Post editor page open. Browser DevTools console open for error monitoring.

## Actions Performed
1. Created an article with a title of 500+ characters (repeated 'A' characters).
2. Created an article with a title containing special characters: `<script>alert('XSS')</script>`.
3. Created an article with Unicode characters in the title: `测试文章 🚀 テスト記事`.
4. Created an article with only whitespace characters in the title.
5. Created an article with an extremely long body (10,000+ characters of Lorem Ipsum).
6. Attempted to submit an article with the body field containing HTML tags (`<h1>`, `<img>`, `<iframe>`).

## Observations / Findings
- **500+ character title**: Article was created successfully with no validation error. The title overflowed the article card on the home feed, breaking the UI layout. No maximum length is enforced.
- **Script injection title**: The `<script>` tag was rendered as plain text, not executed. XSS is properly mitigated through output encoding.
- **Unicode title**: Displayed correctly in all views. No encoding issues.
- **Whitespace-only title**: Article was created with a blank-looking title. The slug became a series of dashes. This should arguably be rejected like an empty title.
- **Long body**: Rendered correctly, no performance issues on the article page.
- **HTML in body**: Tags were stripped or escaped. No rendering of raw HTML. Markdown rendering worked correctly.

## Bugs Found
- **BUG-004** — No maximum length validation on article titles. A 500+ character title is accepted, causing UI overflow on the article feed cards. The UI breaks visually with extremely long titles.
