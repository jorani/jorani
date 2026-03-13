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

    // Find the row where the 4th column (login) matches exactly
    // We use a regex for exact match to avoid partial matches with old test data
    const row = this.page.locator('table#users tbody tr').filter({
      has: this.page.locator('td:nth-child(4)').filter({ hasText: new RegExp(`^${login}$`) })
    }).first();
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
