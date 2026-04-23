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
import { readFileSync } from 'fs'
import { execSync } from 'node:child_process'

async function resolveNextcloudBranch() {
	const appinfo = readFileSync('appinfo/info.xml').toString()
	const match = appinfo.match(/<nextcloud min-version="(\d+)" max-version="(\d+)"/)
	const minVersion = match?.[1]
	const maxVersion = match?.[2]

	// NC_VERSION_TYPE=min uses the minimum supported version; anything else (default) uses max.
	const versionType = process.env.NC_VERSION_TYPE ?? 'max'
	const version = versionType === 'min' ? minVersion : maxVersion

	if (!version) {
		return 'master'
	}

	const refs = execSync('git ls-remote --refs https://github.com/nextcloud/server.git').toString('utf-8')
	return refs.includes(`refs/heads/stable${version}`) ? `stable${version}` : 'master'
}

async function start() {
	const branch = await resolveNextcloudBranch()
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
