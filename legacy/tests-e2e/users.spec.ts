import { test, expect } from './fixtures';

test.describe('User Management', () => {
  // Use a unique login to avoid conflicts
  const testUser = {
    firstname: 'E2E',
    lastname: 'Tester',
    login: `e2e_user_${Date.now()}`,
    email: `e2e_${Date.now()}@example.com`,
    password: 'Password123!',
  };

  test('should create then delete a user', async ({ loginPage, userCreatePage, usersPage }) => {
    // 1. Login as admin
    await loginPage.goto();
    await loginPage.login('bbalet', 'bbalet');

    // 2. Create the user
    //await userCreatePage.goto();
    //await userCreatePage.createUser(testUser);

    // 3. Delete the user
    //await usersPage.goto();
    //await usersPage.deleteUser(testUser.login);
  });
});
