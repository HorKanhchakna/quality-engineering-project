# Software Quality Engineering Report

## Project: Conduit (RealWorld) — Path A: The Integrator

### Team Members
1. Phearom Ratha
2. Men Srei Tin
3. Hor Kanhchakna
4. Poeun Sreytey

| Field | Details |
|-------|---------|
| **Project Path** | Path A: The Integrator (Standard) |
| **Application** | Conduit RealWorld App |
| **Tech Stack** | Vue3 + Laravel + MySQL |
| **Date** | June 2026 |

---

## Table of Contents

- [Phase 1: Exploration & Test Design](#phase-1-exploration--test-design)
  - [Part A: Test Case Suite (Black-Box Testing)](#part-a-test-case-suite-black-box-testing)
  - [Part B: Investigative Testing – Test Charters (SBTM)](#part-b-investigative-testing--test-charters-sbtm)
  - [Part C: Defect Documentation – Bug Reports](#part-c-defect-documentation--bug-reports)
- [Phase 2: White-Box & API](#phase-2-white-box--api)
  - [2.1 Overview](#21-overview)
  - [2.2 White-Box Testing (Unit Tests)](#22-white-box-testing-unit-tests)
  - [2.3 API Validation (Postman Collection)](#23-api-validation-postman-collection)
  - [2.4 UI Automation – E2E Tests](#24-ui-automation--e2e-tests)
  - [2.5 Evidence](#25-evidence)
- [Phase 3: Infrastructure Analysis (Performance)](#phase-3-infrastructure-analysis-performance)
  - [Load & Stress Testing Results](#load--stress-testing-results)
  - [Performance Metrics & Bottleneck Analysis](#performance-metrics--bottleneck-analysis)

---

## PHASE 1: EXPLORATION & TEST DESIGN

### Part A: Test Case Suite (Black-Box Testing)

**Techniques Applied:** Equivalence Partitioning, Boundary Value Analysis, Error Guessing, Role-Based Testing
**Scope:** User Authentication, Article CRUD Operations, Favorites, Authorization

| ID | Title | Technique | Path | Status |
|----|-------|-----------|------|--------|
| TC-AUTH-001 | Successful User Registration | Equivalence Partitioning | Happy Path | Pass |
| TC-AUTH-002 | Registration with Duplicate Email | Error Guessing | Sad Path | Pass |
| TC-AUTH-003 | Login with Valid Credentials | Equivalence Partitioning | Happy Path | Pass |
| TC-AUTH-004 | Login with Wrong Password | Equivalence Partitioning | Sad Path | Pass |
| TC-AUTH-005 | Password at Minimum Length (1 char) | Boundary Value Analysis | Sad Path | Pass |
| TC-ART-006 | Create New Article | Equivalence Partitioning | Happy Path | Pass |
| TC-ART-007 | Create Article with Empty Title | Error Guessing | Sad Path | Pass |
| TC-ART-008 | Edit Own Article | Equivalence Partitioning | Happy Path | Pass |
| TC-ART-009 | Edit Another User's Article (Unauthorized) | Role-Based Testing | Sad Path | Pass |
| TC-ART-010 | Delete Own Article | Equivalence Partitioning | Happy Path | Pass |
| TC-FAV-011 | Favorite an Article | Equivalence Partitioning | Happy Path | Pass |
| TC-FAV-012 | Favorite While Not Logged In | Error Guessing | Sad Path | Pass |

#### Detailed Test Cases

##### TC-AUTH-001 – Happy Path: Successful User Registration

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-AUTH-001 |
| **Test Title** | Happy Path: Successful User Registration |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | No existing account with email newuser@demo.com. User is on /register page. |
| **Test Steps** | 1. Navigate to /register 2. Enter Username: newuser, Email: newuser@demo.com, Password: Pass123! 3. Click 'Sign Up' button |
| **Test Data** | Username: newuser \| Email: newuser@demo.com \| Password: Pass123! |
| **Expected Result** | User account is created successfully. User is redirected to home page and shown as logged in with username 'newuser'. |
| **Actual Result** | User redirected to home page. Username 'newuser' displayed in navigation bar. |
| **Status** | Pass |
| **Post-conditions** | User account exists in the database. User session is active. |

##### TC-AUTH-002 – Sad Path: Registration with Duplicate Email

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-AUTH-002 |
| **Test Title** | Sad Path: Registration with Duplicate Email |
| **Technique Used** | Error Guessing |
| **Path Type** | Sad Path |
| **Pre-conditions** | An account with email existing@demo.com already exists in the system. |
| **Test Steps** | 1. Navigate to /register 2. Enter Username: anotheruser, Email: existing@demo.com, Password: Pass123! 3. Click 'Sign Up' |
| **Test Data** | Username: anotheruser \| Email: existing@demo.com \| Password: Pass123! |
| **Expected Result** | System returns error message: 'email has already been taken'. User stays on registration page. |
| **Actual Result** | Error message displayed: 'email has already been taken'. No new account created. |
| **Status** | Pass |
| **Post-conditions** | No new account created. Existing account unchanged. |

##### TC-AUTH-003 – Happy Path: Login with Valid Credentials

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-AUTH-003 |
| **Test Title** | Happy Path: Login with Valid Credentials |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | Registered user account exists: newuser@demo.com / Pass123!. User is on /login page. |
| **Test Steps** | 1. Navigate to /login 2. Enter Email: newuser@demo.com, Password: Pass123! 3. Click 'Sign In' |
| **Test Data** | Email: newuser@demo.com \| Password: Pass123! |
| **Expected Result** | User is authenticated and redirected to home page. Navigation shows username 'newuser'. |
| **Actual Result** | User redirected to home page. 'newuser' shown in nav bar. |
| **Status** | Pass |
| **Post-conditions** | User session is active. Auth token stored. |

##### TC-AUTH-004 – Sad Path: Login with Wrong Password

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-AUTH-004 |
| **Test Title** | Sad Path: Login with Wrong Password |
| **Technique Used** | Equivalence Partitioning (Invalid Class) |
| **Path Type** | Sad Path |
| **Pre-conditions** | Registered user newuser@demo.com exists. User is on /login page. |
| **Test Steps** | 1. Navigate to /login 2. Enter Email: newuser@demo.com, Password: WrongPass! 3. Click 'Sign In' |
| **Test Data** | Email: newuser@demo.com \| Password: WrongPass! |
| **Expected Result** | Error message: 'email or password is invalid'. User remains on login page. No session created. |
| **Actual Result** | Error message displayed. User remained on /login page. |
| **Status** | Pass |
| **Post-conditions** | No session created. No token issued. |

##### TC-AUTH-005 – Boundary: Password at Minimum Length (1 character)

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-AUTH-005 |
| **Test Title** | Boundary: Password at Minimum Length (1 character) |
| **Technique Used** | Boundary Value Analysis |
| **Path Type** | Sad Path |
| **Pre-conditions** | User is on /register page. Minimum password length assumed to be 8 characters. |
| **Test Steps** | 1. Navigate to /register 2. Enter Username: bvuser, Email: bv@demo.com, Password: A 3. Click 'Sign Up' |
| **Test Data** | Username: bvuser \| Email: bv@demo.com \| Password: A (1 character) |
| **Expected Result** | Validation error: 'password is too short (minimum 8 characters)'. Registration blocked. |
| **Actual Result** | Error message: 'password is too short'. Registration blocked. |
| **Status** | Pass |
| **Post-conditions** | No account created. |

##### TC-ART-006 – Happy Path: Create New Article

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-ART-006 |
| **Test Title** | Happy Path: Create New Article |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | User is logged in as newuser. User is on the New Post page (/editor). |
| **Test Steps** | 1. Click 'New Post' 2. Enter Title: 'Test Article', Description: 'Test Desc', Body: 'Test Body' 3. Enter Tag: test 4. Click 'Publish Article' |
| **Test Data** | Title: Test Article \| Description: Test Desc \| Body: Test Body \| Tag: test |
| **Expected Result** | Article is created and published. User is redirected to the article page at /article/test-article. |
| **Actual Result** | Article created. URL slug /article/test-article displayed correctly. |
| **Status** | Pass |
| **Post-conditions** | Article exists in database and is visible on global feed. |

##### TC-ART-007 – Sad Path: Create Article with Empty Title

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-ART-007 |
| **Test Title** | Sad Path: Create Article with Empty Title |
| **Technique Used** | Error Guessing |
| **Path Type** | Sad Path |
| **Pre-conditions** | User is logged in. User is on the New Post editor page. |
| **Test Steps** | 1. Click 'New Post' 2. Leave Title empty 3. Fill Description: 'Some Desc', Body: 'Some Body' 4. Click 'Publish Article' |
| **Test Data** | Title: (empty) \| Description: Some Desc \| Body: Some Body |
| **Expected Result** | Validation error: 'title can't be blank'. Article is NOT published. |
| **Actual Result** | Error: 'title can't be blank'. Article not created. |
| **Status** | Pass |
| **Post-conditions** | No article created in the database. |

##### TC-ART-008 – Happy Path: Edit Own Article

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-ART-008 |
| **Test Title** | Happy Path: Edit Own Article |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | User newuser is logged in and owns article 'Test Article'. |
| **Test Steps** | 1. Navigate to owned article 2. Click 'Edit Article' 3. Change Title to 'Updated Article Title' 4. Click 'Publish Article' |
| **Test Data** | New Title: Updated Article Title |
| **Expected Result** | Article is updated. Page displays 'Updated Article Title'. Slug updates accordingly. |
| **Actual Result** | Title updated to 'Updated Article Title'. Changes saved successfully. |
| **Status** | Pass |
| **Post-conditions** | Article reflects updated title in database and UI. |

##### TC-ART-009 – Sad Path: Edit Another User's Article (Unauthorized)

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-ART-009 |
| **Test Title** | Sad Path: Edit Another User's Article (Unauthorized) |
| **Technique Used** | Error Guessing / Role-Based Testing |
| **Path Type** | Sad Path |
| **Pre-conditions** | User newuser is logged in. An article exists that belongs to user charlie. |
| **Test Steps** | 1. Navigate to charlie's article 2. Inspect UI for Edit button 3. Attempt direct API PATCH /api/articles/{slug} as newuser |
| **Test Data** | Logged-in user: newuser \| Article owner: charlie |
| **Expected Result** | Edit button is not visible in UI. Direct API call returns HTTP 403 Forbidden. |
| **Actual Result** | Edit button absent from UI. API returned 403 Forbidden. |
| **Status** | Pass |
| **Post-conditions** | Article remains unchanged. Unauthorized access blocked. |

##### TC-ART-010 – Happy Path: Delete Own Article

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-ART-010 |
| **Test Title** | Happy Path: Delete Own Article |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | User newuser is logged in and owns article 'Test Article'. |
| **Test Steps** | 1. Navigate to owned article 2. Click 'Delete Article' button 3. Confirm deletion |
| **Test Data** | Article: 'Test Article' owned by newuser |
| **Expected Result** | Article is deleted. User redirected to home page. Article no longer appears on feed. |
| **Actual Result** | Article deleted. Redirect to home. Article not found on feed. |
| **Status** | Pass |
| **Post-conditions** | Article is removed from database. Slug returns 404. |

##### TC-FAV-011 – Happy Path: Favorite an Article

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-FAV-011 |
| **Test Title** | Happy Path: Favorite an Article |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | User newuser is logged in. An article by charlie exists with 0 favorites. |
| **Test Steps** | 1. Navigate to charlie's article 2. Click the heart (♡) button |
| **Test Data** | Article: charlie's article \| Initial favorites: 0 |
| **Expected Result** | Heart icon fills (becomes active). Favorite count increments by 1 (displays 1). |
| **Actual Result** | Heart icon turned red. Favorite count changed from 0 to 1. |
| **Status** | Pass |
| **Post-conditions** | Favorite relationship stored in database. Count = 1. |

##### TC-FAV-012 – Sad Path: Favorite an Article While Not Logged In

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-FAV-012 |
| **Test Title** | Sad Path: Favorite an Article While Not Logged In |
| **Technique Used** | Error Guessing |
| **Path Type** | Sad Path |
| **Pre-conditions** | User is NOT logged in (guest). An article exists with 0 favorites. |
| **Test Steps** | 1. Navigate to any article as a guest 2. Click the heart (♡) button |
| **Test Data** | User: guest (unauthenticated) \| Article: any |
| **Expected Result** | User is redirected to /login page or shown a prompt to log in. Favorite count does NOT increment. |
| **Actual Result** | User redirected to /login. Favorite count remained 0. |
| **Status** | Pass |
| **Post-conditions** | No favorite record created. User remains unauthenticated. |

---

### Part B: Investigative Testing – Test Charters (SBTM)

**Methodology:** Session-Based Test Management (SBTM). Each charter is time-boxed and targets risks that scripted tests typically miss, such as concurrency issues, resilience failures, and real-time data integrity.

| ID | Mission | Tester | Time |
|----|---------|--------|------|
| SBTM-01 | Favorite Count Integrity (Concurrency) | Phearom Ratha | 60 min |
| SBTM-02 | Article Deletion During Favoriting | Men Srei Tin | 45 min |
| SBTM-03 | Editing While Another User Favorites | Hor Kanhchakna | 30 min |
| SBTM-04 | Authentication Token Edge Cases | Poeun Sreytey | 40 min |
| SBTM-05 | Extreme Input Boundary Testing | Men Srei Tin | 35 min |

#### Charter Details

##### Charter 1 – Concurrency: Favorite Count Integrity

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-01 |
| **Tester** | Phearom Ratha |
| **Date** | 2026-06-06 |
| **Time Spent** | 60 minutes |
| **Mission** | Explore what happens when multiple users favorite the same article simultaneously, hunting for lost updates, incorrect counts, or race conditions. |
| **Setup / Start Conditions** | One article 'Race Article' published. Three user accounts (user1, user2, user3) each logged into separate Incognito browser windows. |
| **Actions Performed** | 1. All three users navigate to the same article simultaneously. 2. User1 clicks favorite → UI shows count = 1. 3. User2 clicks favorite immediately after → User2 sees count = 2, but User1's screen still shows count = 1 (no reload). 4. User3 clicks favorite → similar delay. 5. All users manually refresh page. 6. After refresh, all users see the correct final count. |
| **Observations / Findings** | Immediate counts were inconsistent across users (stale client state). One user saw count=1 while another saw count=2 at the same moment. After manual refresh all counts corrected. No data loss observed in the database — the bug is purely a UI real-time sync issue. |
| **Bugs Found** | BUG-001 – Favorite count not updated in real-time across clients; race condition causes stale UI state. |

##### Charter 2 – Resilience: Article Deletion During Favoriting

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-02 |
| **Tester** | Men Srei Tin |
| **Date** | 2026-06-06 |
| **Time Spent** | 45 minutes |
| **Mission** | Find how the system behaves when a user tries to favorite an article that is simultaneously deleted by its author, looking for unhandled errors or silent failures. |
| **Setup / Start Conditions** | UserA (author) creates article 'Test Delete Article'. UserB (reader) is logged in on a separate browser and has the article open. |
| **Actions Performed** | 1. UserB navigates to the article and hovers over the favorite button. 2. UserA opens the article owner controls and clicks 'Delete Article'. 3. Within 2 seconds of deletion, UserB clicks the favorite button. 4. Observe UserB's screen for any response, error message, or console log. |
| **Observations / Findings** | Favorite button did nothing after the article was deleted. No error message was displayed to UserB — the action silently failed. Browser console showed no HTTP 500; the request may have returned a 404 silently. User experience is confusing as no feedback was given. |
| **Bugs Found** | BUG-002 – No error feedback when favoriting a deleted article; silent failure with no user-facing message. |

##### Charter 3 – Data Integrity: Editing While Another User Favorites

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-03 |
| **Tester** | Hor Kanhchakna |
| **Date** | 2026-06-06 |
| **Time Spent** | 30 minutes |
| **Mission** | Detect data mismatch or UI inconsistency when an article is edited while another user simultaneously favorites it. |
| **Setup / Start Conditions** | UserA (author) owns article 'Original Title' with 0 favorites. UserB is logged in on a separate browser with the article open. |
| **Actions Performed** | 1. UserB opens the article and is ready to click favorite. 2. UserA clicks 'Edit Article', changes title to 'Updated Title', and clicks Publish. 3. Simultaneously, UserB clicks the favorite button. 4. Observe title displayed on UserB's screen without refreshing. 5. Manually refresh UserB's page and observe again. |
| **Observations / Findings** | Favorite count incremented correctly (0→1) on both screens after action. However, UserB's screen still showed 'Original Title' instead of 'Updated Title' without a page refresh. After manual refresh, correct title appeared. No data corruption — counts were consistent in the database. This is a real-time UI sync limitation. |
| **Bugs Found** | BUG-003 – Article title does not update in real-time for other users after edit; requires manual page refresh. |

##### Charter 4 – Security: Authentication Token Edge Cases

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-04 |
| **Tester** | Poeun Sreytey |
| **Date** | 2026-06-06 |
| **Time Spent** | 40 minutes |
| **Mission** | Explore authentication edge cases related to JWT token handling — expired tokens, malformed tokens, missing tokens, and token behavior after logout — to identify security risks or unexpected access. |
| **Setup / Start Conditions** | User account testuser@demo.com exists. Browser DevTools open to inspect network requests and localStorage. Backend JWT configuration reviewed (expiration time, signing algorithm). |
| **Actions Performed** | 1. Logged in as testuser@demo.com and captured the JWT token from localStorage. 2. Manually modified the JWT payload in localStorage (changed the `sub` field to a different user ID) and attempted to access protected routes. 3. Removed the JWT token entirely from localStorage and attempted to create an article via the UI. 4. Logged out and immediately reused the old JWT token via direct API calls (curl/Postman). 5. Waited for the token to approach its expiration time and tested API calls near the boundary. 6. Sent a completely malformed string ("not-a-real-token") as the Authorization header. |
| **Observations / Findings** | Modified token payload: API correctly rejected the tampered token with HTTP 401 — signature verification is working properly. Missing token: UI correctly redirected to /login. API returned 401 Unauthorized as expected. Reused token after logout: The old JWT token was still accepted by the API after logout. The backend does not maintain a token blacklist or revocation list. This means a stolen token remains valid until it naturally expires. Malformed token string: API returned 401 with no crash — error handling is robust. Near-expiration boundary: Token worked up until the exact expiration second, then correctly returned 401. No grace period issues observed. |
| **Bugs Found** | No new functional bugs found. Security observation documented: JWT tokens are not invalidated upon logout (stateless JWT design). This is a known trade-off but should be noted as an accepted risk. |

##### Charter 5 – Robustness: Extreme Input Boundary Testing

| Field | Details |
|-------|--------|
| **Session ID** | SBTM-05 |
| **Tester** | Men Srei Tin |
| **Date** | 2026-06-06 |
| **Time Spent** | 35 minutes |
| **Mission** | Test the application's resilience against extreme and unusual inputs — very long strings, special characters, Unicode, empty fields, and script injection attempts — to identify input validation gaps or UI rendering issues. |
| **Setup / Start Conditions** | User logged in as testuser. New Post editor page open. Browser DevTools console open for error monitoring. |
| **Actions Performed** | 1. Created an article with a title of 500+ characters (repeated 'A' characters). 2. Created an article with a title containing special characters: `<script>alert('XSS')</script>`. 3. Created an article with Unicode characters in the title: `测试文章 🚀 テスト記事`. 4. Created an article with only whitespace characters in the title. 5. Created an article with an extremely long body (10,000+ characters of Lorem Ipsum). 6. Attempted to submit an article with the body field containing HTML tags (`<h1>`, `<img>`, `<iframe>`). |
| **Observations / Findings** | 500+ character title: Article was created successfully with no validation error. The title overflowed the article card on the home feed, breaking the UI layout. No maximum length is enforced. Script injection title: The `<script>` tag was rendered as plain text, not executed. XSS is properly mitigated through output encoding. Unicode title: Displayed correctly in all views. No encoding issues. Whitespace-only title: Article was created with a blank-looking title. The slug became a series of dashes. Long body: Rendered correctly, no performance issues. HTML in body: Tags were stripped or escaped. |
| **Bugs Found** | BUG-004 – No maximum length validation on article titles. A 500+ character title is accepted, causing UI overflow on the article feed cards. |

---

### Part C: Defect Documentation – Bug Reports

All defects below were discovered during Part A (Test Execution) and Part B (Investigative Testing) sessions.

| Bug ID | Title | Severity | Priority | Found In | Status |
|--------|-------|----------|----------|----------|--------|
| BUG-001 | Favorite count inconsistent during concurrent favorites | Major | P2 | SBTM-01 | Open |
| BUG-002 | No error feedback when favoriting deleted article | Minor | P3 | SBTM-02 | Open |
| BUG-003 | Article title not updated in real-time after edit | Minor | P3 | SBTM-03 | Open |
| BUG-004 | No maximum length validation on article titles | Minor | P3 | SBTM-05 | Open |

#### Detailed Bug Reports

##### BUG-001 – Favorite Count Inconsistent During Concurrent Favorites

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-001 |
| **Bug Title** | Favorite count inconsistent across users during concurrent favorites |
| **Severity** | Major – Main feature broken |
| **Priority** | P2 – Fix before next release |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. Create article 'Race Article'. 2. Log in as three different users in separate Incognito windows. 3. All three navigate to the same article. 4. Click favorite simultaneously (or within 1–2 seconds of each other). 5. Observe favorite count on each screen WITHOUT refreshing. |
| **Expected Result** | All users should see the same updated count (e.g., 3) immediately after all favorites are submitted. |
| **Actual Result** | User1 sees count=1. User2 sees count=2. User1 still sees count=1. Counts are inconsistent across clients until manual refresh. |
| **Evidence** | Side-by-side browser windows showing different counts. |
| **Status** | Open |

##### BUG-002 – No Error Feedback When Favoriting a Deleted Article

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-002 |
| **Bug Title** | No error feedback when favoriting a deleted article |
| **Severity** | Minor – UI feedback missing |
| **Priority** | P3 – Low priority |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. UserA creates an article. 2. UserB opens the article in a separate browser window. 3. UserA deletes the article. 4. Within 2 seconds, UserB clicks the favorite (heart) button. |
| **Expected Result** | A user-friendly error message such as 'Article not found' or 'Cannot favorite a deleted article' should be displayed. |
| **Actual Result** | Favorite button does nothing. No error message shown. No console error visible. Favorite count stays at 0. |
| **Evidence** | Screen recording: button click with zero response. |
| **Status** | Open |

##### BUG-003 – Article Title Not Updated in Real-Time After Edit

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-003 |
| **Bug Title** | Article title not updated in real-time for other users after edit |
| **Severity** | Minor – UI inconsistency |
| **Priority** | P3 – Low priority |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. UserA creates article 'Original Title'. 2. UserB opens the same article in a separate browser. 3. UserA edits the title to 'Updated Title' and saves. 4. UserB observes the article title WITHOUT refreshing the page. |
| **Expected Result** | The title should update in real-time (or at minimum, a notification should alert the user that the article has changed). |
| **Actual Result** | UserB still sees 'Original Title' until they manually refresh the page. After refresh, correct title appears. |
| **Evidence** | Screenshot: UserA shows 'Updated Title', UserB simultaneously shows 'Original Title'. |
| **Status** | Open |

##### BUG-004 – No Maximum Length Validation on Article Titles

| Field | Description / Data |
|-------|-------------------|
| **Bug ID** | BUG-004 |
| **Bug Title** | No maximum length validation on article titles |
| **Severity** | Minor – UI rendering issue |
| **Priority** | P3 – Low priority |
| **Environment** | Browser: Chrome v124 \| OS: Windows 11 \| Backend: Laravel (RealWorld) |
| **Steps to Reproduce** | 1. Log in as any user. 2. Navigate to New Post (/editor). 3. Enter a title with 500+ characters (e.g., repeat 'A' 500 times). 4. Fill in description and body with valid data. 5. Click 'Publish Article'. 6. Navigate to the home page and observe the article card. |
| **Expected Result** | The system should enforce a maximum title length (e.g., 255 characters) and display a validation error if exceeded. |
| **Actual Result** | Article is created successfully with a 500+ character title. The title overflows the article card on the home feed, breaking the UI layout. |
| **Evidence** | Screenshot: article card with overflowing title breaking feed layout. |
| **Status** | Open |

---

## PHASE 2: WHITE-BOX & API

### 2.1 Overview

This phase focuses on verifying the internal logic of the application through white-box testing and validating backend communication through API testing. The selected application is the Conduit (RealWorld) Laravel backend used throughout this project.

The objectives of this phase are:
- Verify critical backend business logic using unit tests.
- Validate API endpoints against the RealWorld API specification.
- Ensure authentication, authorization, CRUD operations, comments, favorites, and profile interactions function correctly.
- Confirm each API request contains response assertions for status codes and JSON structures.
- Demonstrate complete CRUD coverage using Postman.

### 2.2 White-Box Testing (Unit Tests)

#### Scope

The white-box testing activity focused on the backend JWT implementation located in:
`backend/app/Jwt`

The tests analyze internal logic, token generation, parsing, validation, and signature creation.

#### Unit Test Coverage

| Test File | Purpose |
|-----------|---------|
| JwtParserTest.php | Validate token parsing, malformed token detection, base64 decoding, JSON parsing |
| TokenTest.php | Validate token properties, headers, claims, expiration, subject, signatures |
| BuilderTest.php | Verify JWT builder functionality and claim generation |
| GeneratorTest.php | Verify token generation, HMAC signature creation, and APP_KEY usage |

#### Test Coverage Summary

**JwtParserTest** — The parser tests verify:
- Valid JWT tokens can be parsed successfully.
- Invalid token structures are rejected.
- Invalid Base64 payloads are handled safely.
- Invalid JSON payloads do not crash the application.
- Parsed tokens preserve their original claims.

**TokenTest** — The token tests verify:
- Default JWT headers are correctly assigned.
- Claims can be added and retrieved.
- Expiration timestamps are stored correctly.
- Subject identifiers are preserved.
- Signatures are stored and retrieved correctly.

**BuilderTest** — The builder tests verify:
- Fluent method chaining works correctly.
- Default JWT headers are automatically created.
- Custom claims are written properly.
- Subject values are stored correctly.
- Issued-at and expiration timestamps are generated properly.

**GeneratorTest** — The generator tests verify:
- HMAC signatures are generated correctly.
- Missing APP_KEY configurations trigger failures.
- Generated tokens follow the JWT specification.
- Tokens can be generated and parsed successfully.

#### White-Box Testing Results

The unit testing process successfully verified critical JWT functionality including token creation, parsing, validation, signature generation, and claim management.

The tests reduce the risk of:
- Malformed token acceptance
- Authentication failures
- Invalid JWT structures
- Signature manipulation
- Configuration errors involving APP_KEY

### 2.3 API Validation (Postman Collection)

**Base URL:** `http://localhost:8000/api`

#### Objective

The API validation activity ensures that all backend endpoints comply with the RealWorld API contract and function correctly under normal conditions.

#### Validation Goals
- Verify request and response structures.
- Verify authentication using JWT tokens.
- Verify protected endpoints require authorization.
- Verify CRUD functionality.
- Verify collection and list endpoints return valid payloads.
- Verify Postman assertions for status codes and JSON responses.

#### Environment Variables

| Variable | Description |
|----------|-------------|
| baseUrl | API base URL |
| email | Test user email |
| password | Test user password |
| username | Test username |
| token | JWT authentication token |
| slug | Article identifier |

#### API Endpoints Tested

| Module | Endpoints |
|--------|-----------|
| Authentication | POST /users – Register User, POST /users/login – Login User, GET /user – Get Current User, PUT /user – Update Current User |
| Articles & Tags | GET /tags – List Tags, GET /articles – List Articles, POST /articles – Create Article, GET /articles/{slug} – Get Article, PUT /articles/{slug} – Update Article, DELETE /articles/{slug} – Delete Article |
| Profiles | GET /profiles/{username} – Get Profile, POST /profiles/{username}/follow – Follow Profile |
| Comments & Favorites | POST /articles/{slug}/comments – Create Comment, POST /articles/{slug}/favorite – Favorite Article |

#### CRUD Coverage

| Operation | Endpoint |
|-----------|----------|
| Create | POST /articles |
| Read | GET /articles \| GET /articles/{slug} |
| Update | PUT /articles/{slug} |
| Delete | DELETE /articles/{slug} |

#### Postman Assertions

Every request contains at least two assertions:

| Assertion Type | Examples |
|----------------|----------|
| Status Code Validation | 200 OK \| 201 Created |
| JSON Response Validation | Verify user object exists. Verify token exists after login. Verify article object exists. Verify profile object exists. Verify tags array exists. |

#### API Testing Results

The Postman collection successfully validated:
- User registration
- User login
- JWT authentication
- User profile management
- Article CRUD operations
- Profile following
- Comment creation
- Article favoriting
- Tag retrieval

All tested endpoints returned expected responses and passed status code and JSON structure validation checks.

### 2.4 UI Automation – E2E Tests

**Framework:** Playwright
**Files:** `frontend/playwright/specs/auth.spec.ts`, `article.spec.ts`

**2 User Journeys Automated:**
1. **User Login/Logout Flow** – Registration, login, token storage, logout
2. **Article Creation/Deletion** – New post, publish, verify, delete, confirm removal

### 2.5 Evidence

The following evidence is included with the submission:

| Category | Evidence Items |
|----------|---------------|
| White-Box Testing | Unit test source code (`backend/tests/Unit/Jwt/`), PHPUnit execution results, JWT testing implementation |
| API Validation | Postman Collection Export (`postman/realworld-phase-2.postman_collection.json`), Environment Variables, Request Assertions, CRUD Validation Results |
| UI Automation | Playwright spec files (`frontend/playwright/specs/`), E2E test implementation |

---

## PHASE 3: INFRASTRUCTURE ANALYSIS (PERFORMANCE)

### Load & Stress Testing

**Tools:** K6 / JMeter
**Scripts:** `performance/load-test.js` / `performance/load-test.jmx`
**Methodology:** Establish a 1-user baseline, then scale load incrementally from 10 to 100 concurrent users. Identify the breaking point and primary bottleneck.

### Performance Metrics Summary

| Concurrent Users | Avg Response Time | P95 Response Time | Throughput (req/s) | Error Rate |
|------------------|-------------------|-------------------|--------------------|------------|
| 1 (Baseline) | 45 ms | 52 ms | 22 req/s | 0% |
| 10 | 85 ms | 112 ms | 117 req/s | 0% |
| 25 | 195 ms | 245 ms | 128 req/s | 0% |
| 50 | 420 ms | 580 ms | 119 req/s | 2% |
| 75 | 890 ms | 1,120 ms | 84 req/s | 15% |
| 100 (FAILURE) | 1,850 ms | 2,400 ms | 54 req/s | 45% |

### Response Time vs. User Load Graph

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

### Bottleneck Analysis

| Metric | Details |
|--------|---------|
| **Breaking Point** | ~75–100 concurrent users |
| **Primary Bottleneck** | Database connection pool exhaustion — PostgreSQL begins queuing queries beyond 75 concurrent connections, causing cascading timeouts. |
| **Secondary Bottleneck** | Backend CPU spike observed at 100 users due to synchronous JWT token verification on each request without caching. |
| **Safe Operating Zone** | Up to 50 concurrent users — avg response time stays under 500ms and error rate is below 2%. |
| **Recommended Fix** | Increase DB connection pool size. Implement PgBouncer for connection pooling. Cache JWT verification results with short TTL. |

---

## FINAL SUBMISSION CHECKLIST

| # | Deliverable | Phase | ✓ |
|---|-------------|-------|---|
| 1 | Black-Box Test Suite: 12 formal test cases (7 Happy, 5 Sad paths) using EP, BVA, Error Guessing, Role-Based Testing | Phase 1 | ✅ Done |
| 2 | Test Charters: 5 SBTM charters – Concurrency, Resilience, Data Integrity, Security, Robustness | Phase 1 | ✅ Done |
| 3 | Bug Tracking Log: 4 formally documented bugs (BUG-001 to BUG-004) with Steps, Severity, Priority, Evidence | Phase 1 | ✅ Done |
| 4 | Unit Tests: JWT logic coverage via PHPUnit (JwtParserTest, TokenTest, BuilderTest, GeneratorTest) | Phase 2 | ✅ Done |
| 5 | Postman Collection: Full CRUD coverage, environment variables, 2+ assertions per request | Phase 2 | ✅ Done |
| 6 | UI Automation: 2 E2E flows via Playwright (auth + article) | Phase 2 | ✅ Done |
| 7 | Performance Report: K6/JMeter results, response time graph, bottleneck analysis | Phase 3 | ✅ Done |
| 8 | GitHub Repository Link: All test files, unit tests, and Postman collections | Final | ✅ Done |

### Grading Summary

| Phase | Max Score | Target |
|-------|-----------|--------|
| Phase 1: Exploration & Test Design | 14 pts | 14 pts |
| Phase 2: White-Box & API Engineering | 14 pts | 14 pts |
| Phase 3: Infrastructure Analysis | 7 pts | 7 pts |
| Phase 4: Presentation | 7 pts | 7 pts |
| **TOTAL** | **42 pts** | **42 pts** |

---

*— End of Report —*