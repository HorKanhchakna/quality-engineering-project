# Black-Box Test Case Suite

**Project Name:** Conduit (RealWorld) — Path A: The Integrator
**Module Names:** User Authentication, Article Management, Favorites
**Testers:** Hor Kanhchakna, Poeun Sreytey
**Date:** June 2026

**Techniques Applied:** Equivalence Partitioning, Boundary Value Analysis, Error Guessing, Role-Based Testing
**Scope:** User Authentication, Article CRUD Operations, Favorites, Authorization

---

## Summary Table

| ID | Title | Technique | Path | Status |
|----|-------|-----------|------|--------|
| TC-AUTH-001 | Successful User Registration | Equivalence Partitioning | Happy | Pass |
| TC-AUTH-002 | Registration with Duplicate Email | Error Guessing | Sad | Pass |
| TC-AUTH-003 | Login with Valid Credentials | Equivalence Partitioning | Happy | Pass |
| TC-AUTH-004 | Login with Wrong Password | Equivalence Partitioning | Sad | Pass |
| TC-AUTH-005 | Password at Minimum Length (1 char) | Boundary Value Analysis | Sad | Pass |
| TC-ART-006 | Create New Article | Equivalence Partitioning | Happy | Pass |
| TC-ART-007 | Create Article with Empty Title | Error Guessing | Sad | Pass |
| TC-ART-008 | Edit Own Article | Equivalence Partitioning | Happy | Pass |
| TC-ART-009 | Edit Another User's Article (Unauthorized) | Role-Based Testing | Sad | Pass |
| TC-ART-010 | Delete Own Article | Equivalence Partitioning | Happy | Pass |
| TC-FAV-011 | Favorite an Article | Equivalence Partitioning | Happy | Pass |
| TC-FAV-012 | Favorite While Not Logged In | Error Guessing | Sad | Pass |

---

## Detailed Test Cases

### TC-AUTH-001 — Happy Path: Successful User Registration

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

---

### TC-AUTH-002 — Sad Path: Registration with Duplicate Email

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

---

### TC-AUTH-003 — Happy Path: Login with Valid Credentials

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

---

### TC-AUTH-004 — Sad Path: Login with Wrong Password

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

---

### TC-AUTH-005 — Boundary: Password at Minimum Length (1 character)

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

---

### TC-ART-006 — Happy Path: Create New Article

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

---

### TC-ART-007 — Sad Path: Create Article with Empty Title

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

---

### TC-ART-008 — Happy Path: Edit Own Article

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

---

### TC-ART-009 — Sad Path: Edit Another User's Article (Unauthorized)

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

---

### TC-ART-010 — Happy Path: Delete Own Article

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

---

### TC-FAV-011 — Happy Path: Favorite an Article

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-FAV-011 |
| **Test Title** | Happy Path: Favorite an Article |
| **Technique Used** | Equivalence Partitioning (Valid Class) |
| **Path Type** | Happy Path |
| **Pre-conditions** | User newuser is logged in. An article by charlie exists with 0 favorites. |
| **Test Steps** | 1. Navigate to charlie's article 2. Click the heart button |
| **Test Data** | Article: charlie's article \| Initial favorites: 0 |
| **Expected Result** | Heart icon fills (becomes active). Favorite count increments by 1 (displays 1). |
| **Actual Result** | Heart icon turned red. Favorite count changed from 0 to 1. |
| **Status** | Pass |
| **Post-conditions** | Favorite relationship stored in database. Count = 1. |

---

### TC-FAV-012 — Sad Path: Favorite an Article While Not Logged In

| Field | Description / Data |
|-------|-------------------|
| **Test Case ID** | TC-FAV-012 |
| **Test Title** | Sad Path: Favorite an Article While Not Logged In |
| **Technique Used** | Error Guessing |
| **Path Type** | Sad Path |
| **Pre-conditions** | User is NOT logged in (guest). An article exists with 0 favorites. |
| **Test Steps** | 1. Navigate to any article as a guest 2. Click the heart button |
| **Test Data** | User: guest (unauthenticated) \| Article: any |
| **Expected Result** | User is redirected to /login page or shown a prompt to log in. Favorite count does NOT increment. |
| **Actual Result** | User redirected to /login. Favorite count remained 0. |
| **Status** | Pass |
| **Post-conditions** | No favorite record created. User remains unauthenticated. |
