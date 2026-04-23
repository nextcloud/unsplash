/*!
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: MIT
 */

import {
	configureNextcloud,
	startNextcloud,
	stopNextcloud,
	waitOnNextcloud,
} from '@nextcloud/e2e-test-server/docker'

async function start() {
	// NC_SERVER_BRANCH is set by the CI matrix via icewind1991/nextcloud-version-matrix.
	// Fall back to 'master' for local runs.
	const branch = process.env.NC_SERVER_BRANCH ?? 'master'
	process.stdout.write(`Starting Nextcloud on branch: ${branch}\n`)

	return await startNextcloud(branch, true, {
		exposePort: 8089,
	})
}

async function stop() {
	process.stderr.write('Stopping Nextcloud server…\n')
	await stopNextcloud()
	// eslint-disable-next-line n/no-process-exit
	process.exit(0)
}

process.on('SIGTERM', stop)
process.on('SIGINT', stop)

// Start the Nextcloud docker container
const ip = await start()
await waitOnNextcloud(ip)
await configureNextcloud(['unsplash'])

// Idle to wait for shutdown
while (true) {
	await new Promise((resolve) => setTimeout(resolve, 5000))
}
