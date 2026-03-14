import { Page, expect } from '@playwright/test';

export class UsersPage {
  constructor(private readonly page: Page) {}

  async goto() {
    await this.page.goto('/users');
    // Ensure the table is loaded
    await expect(this.page.locator('table#users')).toBeVisible();
  }

  async deleteUser(login: string) {
    // Search for the user using the search box of Datatables
    // We wait for the filter wrapper which indicates Datatables has initialized
    const filterWrapper = this.page.locator('.dataTables_filter');
    await expect(filterWrapper).toBeVisible({ timeout: 15000 });
    
    const searchBox = filterWrapper.locator('input');
    await searchBox.fill(login);

    // Wait for Datatables to filter and update its info text
    await expect(this.page.locator('#users_info')).toContainText(/1 to 1 of 1/i, { timeout: 10000 });
    
    // Now get the visible row from the tbody
    const row = this.page.locator('#users tbody tr').first();
    await expect(row).toBeVisible();

    const deleteButton = row.locator('.confirm-delete');
    await deleteButton.click();

    // Confirm deletion in the modal
    const confirmButton = this.page.locator('#action-delete');
    await expect(confirmButton).toBeVisible();
    await confirmButton.click();

    // Verify row disappearance
    await expect(row).not.toBeVisible({ timeout: 10000 });
  }
}
