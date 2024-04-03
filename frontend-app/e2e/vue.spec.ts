import { test, expect } from '@playwright/test';

// See here how to get started:
// https://playwright.dev/docs/intro
test('visits the app root url', async ({ page }) => {
  await page.goto('/search');
  await expect(page.locator('[data-e2e-page-title]')).toHaveText('Search');
})