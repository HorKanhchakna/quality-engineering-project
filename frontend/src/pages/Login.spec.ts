import { expect, test } from '@playwright/test'

// ============================================================
// E2E Flow 1: User Login
// Tests the complete login journey including validation errors
// ============================================================

test.describe('E2E Flow 1: User Login', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/#/login')
  })

  test('TC-E2E-001 | Happy Path: successful login with valid credentials', async ({
    page,
  }) => {
    // Fill in valid credentials (seeded test user from docker)
    await page.getByPlaceholder('Email').fill('alex@demo.com')
    await page.getByPlaceholder('Password').fill('password')

    // Click Sign In
    await page.getByRole('button', { name: 'Sign in' }).click()

    // Should redirect to home page
    await expect(page).toHaveURL('/#/')

    // Username should appear in the navigation bar
    await expect(page.getByRole('navigation')).toContainText('alex')

    // "New Post" link should be visible (only shown when logged in)
    await expect(page.getByRole('link', { name: 'New Post' })).toBeVisible()
  })

  test('TC-E2E-002 | Sad Path: login with wrong password shows error', async ({
    page,
  }) => {
    await page.getByPlaceholder('Email').fill('alex@demo.com')
    await page.getByPlaceholder('Password').fill('wrongpassword123')
    await page.getByRole('button', { name: 'Sign in' }).click()

    // Should stay on login page
    await expect(page).toHaveURL('/#/login')

    // Error message should appear
    await expect(
      page.locator('.error-messages, [class*="error"], ul li'),
    ).toBeVisible()
  })

  test('TC-E2E-003 | Sad Path: login with empty fields', async ({ page }) => {
    // Try to submit empty form
    await page.getByRole('button', { name: 'Sign in' }).click()

    // Should not navigate away
    await expect(page).toHaveURL('/#/login')
  })

  test('TC-E2E-004 | Navigation: clicking Sign In link from home navigates to login', async ({
    page,
  }) => {
    await page.goto('/#/')
    await page.getByRole('link', { name: 'Sign in' }).click()
    await expect(page).toHaveURL('/#/login')
    await expect(page.getByRole('button', { name: 'Sign in' })).toBeVisible()
  })

  test('TC-E2E-005 | After login: user can log out successfully', async ({
    page,
  }) => {
    // Login first
    await page.getByPlaceholder('Email').fill('alex@demo.com')
    await page.getByPlaceholder('Password').fill('password')
    await page.getByRole('button', { name: 'Sign in' }).click()
    await expect(page).toHaveURL('/#/')

    // Navigate to settings to find logout
    await page.getByRole('link', { name: 'Settings' }).click()
    await expect(page).toHaveURL('/#/settings')

    // Click the logout button
    await page.getByRole('button', { name: /logout|sign out/i }).click()

    // Should redirect to home as guest — "Sign in" link should appear in nav
    await expect(page.getByRole('link', { name: 'Sign in' })).toBeVisible()
  })
})

// ============================================================
// E2E Flow 2: Create a Post (Article)
// Tests the full article creation journey for a logged-in user
// ============================================================

// Helper: login before article tests
async function loginAs(page: any, email = 'alex@demo.com', password = 'password') {
  await page.goto('/#/login')
  await page.getByPlaceholder('Email').fill(email)
  await page.getByPlaceholder('Password').fill(password)
  await page.getByRole('button', { name: 'Sign in' }).click()
  await expect(page).toHaveURL('/#/')
}

test.describe('E2E Flow 2: Create a Post (Article)', () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page)
  })

  test('TC-E2E-006 | Happy Path: create a new article with all fields', async ({ page }) => {
    // Navigate to new post editor
    await page.getByRole('link', { name: 'New Post' }).click()
    await expect(page).toHaveURL('/#/article/create')

    // Fill in article details
    const timestamp = Date.now()
    const title = `E2E Test Article ${timestamp}`

    await page.getByPlaceholder('Article Title').fill(title)
    await page.getByPlaceholder("What's this article about?").fill('This is an automated E2E test description')
    await page.getByPlaceholder('Write your article (in markdown)').fill('## E2E Test Body\n\nThis article was created by Playwright.')

    // Add a tag
    const tagInput = page.getByPlaceholder('Enter tags')
    await tagInput.fill('e2e-test')
    await tagInput.press('Enter')

    // Publish
    await page.getByRole('button', { name: 'Publish Article' }).click()

    // Should redirect to the article page
    await expect(page).toHaveURL(/\/#\/article\//)

    // Article title should be visible on the page
    await expect(page.getByRole('heading', { level: 1 })).toContainText(title)

    // Tag should appear
    await expect(page.locator('.tag-list')).toContainText('e2e-test')
  })

  test('TC-E2E-007 | Sad Path: cannot publish article with empty title', async ({ page }) => {
    await page.getByRole('link', { name: 'New Post' }).click()
    await expect(page).toHaveURL('/#/article/create')

    // Fill only description and body, leave title empty
    await page.getByPlaceholder("What's this article about?").fill('Test description')
    await page.getByPlaceholder('Write your article (in markdown)').fill('Test body content')

    await page.getByRole('button', { name: 'Publish Article' }).click()

    // Should stay on editor page (button is disabled when title is empty)
    await expect(page).toHaveURL('/#/article/create')
  })

  test('TC-E2E-008 | Happy Path: edit own article successfully', async ({ page }) => {
    // First create an article
    await page.getByRole('link', { name: 'New Post' }).click()
    const timestamp = Date.now()
    const originalTitle = `Edit Test ${timestamp}`

    await page.getByPlaceholder('Article Title').fill(originalTitle)
    await page.getByPlaceholder("What's this article about?").fill('Original description')
    await page.getByPlaceholder('Write your article (in markdown)').fill('Original body')
    await page.getByRole('button', { name: 'Publish Article' }).click()
    await expect(page).toHaveURL(/#\/article\//)

    // Click Edit Article button
    await page.getByRole('link', { name: /edit article/i }).click()
    await expect(page).toHaveURL(/#\/article\/.+\/edit/)

    // Update the title
    const updatedTitle = `Updated: ${originalTitle}`
    await page.getByPlaceholder('Article Title').clear()
    await page.getByPlaceholder('Article Title').fill(updatedTitle)
    await page.getByRole('button', { name: 'Publish Article' }).click()

    // Should redirect to article page with updated title
    await expect(page.getByRole('heading', { level: 1 })).toContainText(updatedTitle)
  })

  test('TC-E2E-009 | Happy Path: delete own article', async ({ page }) => {
    // Create an article to delete
    await page.getByRole('link', { name: 'New Post' }).click()
    const title = `Delete Me ${Date.now()}`

    await page.getByPlaceholder('Article Title').fill(title)
    await page.getByPlaceholder("What's this article about?").fill('Will be deleted')
    await page.getByPlaceholder('Write your article (in markdown)').fill('Body content')
    await page.getByRole('button', { name: 'Publish Article' }).click()
    await expect(page).toHaveURL(/#\/article\//)

    // Click Delete Article
    await page.getByRole('button', { name: /delete article/i }).click()

    // Should redirect back to home
    await expect(page).toHaveURL('/#/')
  })

  test('TC-E2E-010 | Unauthenticated: clicking New Post redirects to login', async ({ page }) => {
    // Logout by clearing storage
    await page.evaluate(() => localStorage.clear())
    await page.goto('/#/')

    // Try navigating directly to editor
    await page.goto('/#/article/create')

    // Should redirect to login or show unauthorized state
    await expect(page).toHaveURL(/#\/login|#\/register|#\/$/)
  })
})
