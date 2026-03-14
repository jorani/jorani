import { test, expect } from './fixtures';

test.describe('Login flow', () => {
  test('should login successfully with valid credentials', async ({ loginPage, page }) => {
    // Navigate to the login page and authenticate
    await loginPage.goto();
    await loginPage.login('bbalet', 'bbalet');
    await loginPage.expectLoginSuccess();

    // Verify that the user is actually connected (logout link should be visible)
    const logoutLink = page.locator('a[href*="session/logout"]');
    await expect(logoutLink).toBeVisible();
  });

  test('should display an error message with invalid credentials', async ({ loginPage }) => {
    await loginPage.goto();

    // Use invalid credentials
    await loginPage.login('wronguser', 'wrongpass');

    // Check for the presence of an error message alert
    await loginPage.expectLoginError();
  });
});
