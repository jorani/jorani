import { test, expect } from './fixtures';

test('la page d’accueil répond', async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveTitle(/.+/);
});
