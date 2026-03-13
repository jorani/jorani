import { test as base, expect, Page } from '@playwright/test';
import { LoginPage } from './pages/LoginPage';
import { UserCreatePage } from './pages/UserCreatePage';
import { UsersPage } from './pages/UsersPage';

type AppFixtures = {
    page: Page;
    loginPage: LoginPage;
    userCreatePage: UserCreatePage;
    usersPage: UsersPage;
};

const PHP_ERROR_PATTERNS: { name: string; regex: RegExp }[] = [
    { name: 'Warning', regex: /\bWarning\b/i },
    { name: 'Notice', regex: /\bNotice\b/i },
    { name: 'Fatal error', regex: /\bFatal error\b/i },
    { name: 'Parse error', regex: /\bParse error\b/i },
    { name: 'Deprecated', regex: /\bDeprecated\b/i },
    { name: 'Uncaught', regex: /\bUncaught\b/i },
    { name: 'Strict Standards', regex: /\bStrict Standards\b/i },
];

export const test = base.extend<AppFixtures>({
    loginPage: async ({ page }, use) => {
        await use(new LoginPage(page));
    },
    userCreatePage: async ({ page }, use) => {
        await use(new UserCreatePage(page));
    },
    usersPage: async ({ page }, use) => {
        await use(new UsersPage(page));
    },
    page: async ({ page }, use) => {
        const consoleErrors: string[] = [];

        page.on('console', msg => {
            // Store browser console errors for post-test verification
            if (msg.type() === 'error') {
                consoleErrors.push(`[console.${msg.type()}] ${msg.text()}`);
            }
        });

        page.on('pageerror', error => {
            // Store uncaught JavaScript exceptions raised by the page
            consoleErrors.push(`[pageerror] ${error.message}`);
        });

        await use(page);

        // Assert that the page did not have hard JS crashes
        // We only fail on [pageerror]. [console.error] often contains non-breaking items like missing icons (404)
        const jsCrashes = consoleErrors.filter(e => e.startsWith('[pageerror]'));
        expect(
            jsCrashes,
            `JavaScript crashes were detected:\n${jsCrashes.join('\n')}`
        ).toEqual([]);

        // Read visible text from the page body to detect rendered PHP errors
        // We exclude the CodeIgniter Profiler and other debug tools if present
        const body = page.locator('body');
        let bodyText = (await body.textContent()) ?? '';

        // Remove profiler content from search to avoid false positives in debug bar
        const profiler = page.locator('#codeigniter_profiler, .phpdebugbar');
        if (await profiler.count() > 0) {
            const profilerText = (await profiler.allTextContents()).join(' ');
            bodyText = bodyText.replace(profilerText, '');
        }

        const matchedPatterns = PHP_ERROR_PATTERNS
            .filter(entry => entry.regex.test(bodyText))
            .map(entry => entry.name);

        if (matchedPatterns.length > 0) {
            console.log(`[DEBUG] Found PHP error patterns: ${matchedPatterns.join(', ')} on URL: ${page.url()}`);
        }

        expect(
            matchedPatterns,
            `Rendered PHP error patterns were detected: ${matchedPatterns.join(', ')}\n` +
            `Body excerpt:\n${bodyText.slice(0, 4000)}`
        ).toEqual([]);
    },
});

export { expect };
