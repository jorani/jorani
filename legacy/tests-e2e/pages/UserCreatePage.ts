import { Page, expect } from '@playwright/test';

export class UserCreatePage {
  constructor(private readonly page: Page) {}

  async goto() {
    await this.page.goto('/users/create');
    await expect(this.page.locator('#firstname')).toBeVisible();
  }

  async createUser(details: {
    firstname: string;
    lastname: string;
    login: string;
    email: string;
    password?: string;
  }) {
    // Fill basic info first (this triggers auto-generation of login in JS)
    await this.page.fill('#firstname', details.firstname);
    await this.page.fill('#lastname', details.lastname);
    
    // Trigger any auto-fill JS by focusing another field
    await this.page.focus('#email');
    await this.page.fill('#email', details.email);
    
    // Force the login value LAST to overwrite any auto-generated value
    const loginField = this.page.locator('#login');
    await loginField.fill(details.login);
    await loginField.dispatchEvent('change');
    await loginField.blur();
    
    // Double check that JS didn't overwrite our value (Jorani has auto-generation logic)
    const actualLogin = await loginField.inputValue();
    if (actualLogin !== details.login) {
        await loginField.fill(details.login);
        await loginField.dispatchEvent('change');
    }
    
    if (details.password) {
      await this.page.fill('#password', details.password);
    } else {
      // If no password provided, use the generate button
      await this.page.click('#cmdGeneratePassword');
    }

    // Set manager to "himself" to simplify CI tests
    await this.page.click('#cmdSelfManager');

    // Submit the form
    await this.page.click('#send');

    // Redirection to the user list usually follows a successful creation
    await expect(this.page).toHaveURL(/.*users/);
  }
}
