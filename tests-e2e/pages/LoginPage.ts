import { Page, expect } from '@playwright/test';

export class LoginPage {
  constructor(private readonly page: Page) {}

  async goto() {
    await this.page.goto('/session/login');
    await expect(this.page.locator('#login')).toBeVisible();
  }

  async login(username = 'bbalet', password = 'bbalet') {
    await this.page.fill('#login', username);
    await this.page.fill('#password', password);
    await this.page.click('button[type="submit"]');
  }

  async expectLoginSuccess() {
    // Default redirection after login is often /home or /leaves
    await expect(this.page).toHaveURL(/.*(home|leaves)/);
  }

  async expectLoginError() {
    // Jorani uses .alert class for flash messages. We only want the visible one.
    await expect(this.page.locator('.alert:visible').first()).toBeVisible();
  }
}
