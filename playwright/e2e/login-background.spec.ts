/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { expect, test } from '@playwright/test'

test.describe('Login page background', () => {
	test('Login page has a custom background-image stylesheet link', async ({ page }) => {
		await page.goto('/index.php/login')

		// The BeforeTemplateRenderedEventListener injects a <link> pointing to
		// the unsplash CSS endpoint when the login style is enabled (default).
		const link = page.locator('link[rel="stylesheet"]').filter({
			has: page.locator('[href*="/apps/unsplash/api/login.css"]'),
		})
		await expect(link).toHaveCount(1)
	})

	test('Login CSS endpoint returns background-image CSS', async ({ request }) => {
		const response = await request.get('/apps/unsplash/api/login.css')

		expect(response.status()).toBe(200)
		expect(response.headers()['content-type']).toContain('text/css')

		const body = await response.text()
		expect(body).toContain('background-image: url(')
	})

	test('Login page body has a non-default background-image applied', async ({ page }) => {
		await page.goto('/index.php/login')

		// Wait until the login form is visible so stylesheets have been applied.
		await page.locator('body#body-login').waitFor({ state: 'visible' })

		const backgroundImage = await page.evaluate(
			() => window.getComputedStyle(document.body).backgroundImage,
		)

		expect(backgroundImage).not.toBe('none')
		expect(backgroundImage).not.toBe('')
	})
})
