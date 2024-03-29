<?php
/**
 * ownCloud
 *
 * @author Lukas Reschke <lukas@owncloud.com>
 * @copyright Copyright (c) 2016, ownCloud GmbH.
 *
 *
 * This code is covered by the ownCloud Commercial License.
 *
 * You should have received a copy of the ownCloud Commercial License
 * along with this program. If not, see <https://owncloud.com/licenses/owncloud-commercial/>.
 *
 */

/**
 * Welcome to the almighty configuration file. In this file the update definitions for each version are released. Please
 * make sure to read below description of the config format carefully before proceeding.
 *
 * ownCloud updates are delivered by a release channel, at the moment the following channels are available:
 *
 * - production
 * - stable
 * - beta
 * - daily
 *
 * With exception of daily (which is a daily build of master) all of them need to be configured manually. The config
 * array looks like the following:
 *
 * 'production' => [
 * 	'8.2' => [
 * 		'latest' => '8.2.3',
 * 		'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
 * 		// downloadUrl is an optional entry, if not specified the URL is generated using https://download.owncloud.org/community/owncloud-'.$newVersion['latest'].'.zip
 * 		'downloadUrl' => 'https://download.owncloud.org/foo.zip',
 * 	],
 * ]
 *
 * In this case if a ownCloud with the major release of 8.2 sends an update request the 8.2.3 version is returned if the
 * current ownCloud version is below 8.2.
 *
 * The search for releases in the config array is fuzzy and happens as following:
 * 	1. Major.Minor.Maintenance.Revision
 * 	2. Major.Minor.Maintenance
 * 	3. Major.Minor
 * 	4. Major
 *
 * Once a result has been found this one is taken. This allows it to define an update order in case some releases should
 * not be skipped. Let's take a look at an example:
 *
 * 'production' => [
 * 	'8.2.0' => [
 * 		'latest' => '8.2.1',
 * 		'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
 * 	],
 * 	'8.2' => [
 * 		'latest' => '8.2.4',
 * 		'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
 * 	],
 * 	'8.2.4' => [
 * 		'latest' => '9.0.0',
 * 		'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
 * 	],
 * ]
 *
 * This configuration array would have the following meaning:
 *
 * 1. 8.2.0 instances would be delivered 8.2.1
 * 2. All instances below 8.2.4 EXCEPT 8.2.0 and 8.2.4 would be delivered 8.2.4
 * 3. 8.2.4 instances get 9.0.0 delivered
 *
 * Oh. And be a nice person and also adjust the integration tests at /tests/integration/features/update.feature after doing
 * a change to the update logic. That way you can also ensure that your changes will do what you wanted them to do. The
 * tests are automatically executed on Travis or you can do it locally:
 *
 * 	- php -S localhost:8888 update-server/index.php &
 * 	- tests/integration/ && ../../vendor/bin/behat .
 */

return [
	'production' => [
	],
	'stable' => [
	],
	'beta' => [
	],
	'daily' => [
		'10.8' => [
			'downloadUrl' => 'https://download.owncloud.org/community/owncloud-daily-master.zip',
			'web' => 'https://doc.owncloud.org/server/10.8/admin_manual/maintenance/upgrade.html',
		],
	],
	// to prevent individual channels from bloating all upgrade path common for all channels go below
	// if you move anything here make sure you updated 'eol_latest' key 
	'eol' => [
		// 10.8.0 is the most recent with PHP 7.2 support. So 10.4.1 - 10.7.0 should always update through it
		'10.7' => [
			'latest' => '10.8.0',
			'web' => 'https://doc.owncloud.org/server/10.7/admin_manual/maintenance/upgrade.html',
		],
		'10.6' => [
			'latest' => '10.8.0',
			'web' => 'https://doc.owncloud.org/server/10.6/admin_manual/maintenance/upgrade.html',
		],
		'10.5' => [
			'latest' => '10.8.0',
			'web' => 'https://doc.owncloud.org/server/10.5/admin_manual/maintenance/upgrade.html',
		],
		'10.4.100' => [
			'latest' => '10.8.0',
			'web' => 'https://doc.owncloud.org/server/10.4/admin_manual/maintenance/upgrade.html',
		],
		'10.4.1' => [
			'latest' => '10.8.0',
			'web' => 'https://doc.owncloud.org/server/10.4/admin_manual/maintenance/upgrade.html',
		],
		// 10.4.1 is the most recent with PHP 7.1 support. So 10.3.0 - 10.4.0 should always update through it
		'10.4.0' => [
			'latest' => '10.4.1',
			'web' => 'https://doc.owncloud.org/server/10.4/admin_manual/maintenance/upgrade.html',
		],
		'10.3.100' => [
			'latest' => '10.4.1',
			'web' => 'https://doc.owncloud.org/server/10.3/admin_manual/maintenance/upgrade.html',
		],
		'10.3.2' => [
			'latest' => '10.4.1',
			'web' => 'https://doc.owncloud.org/server/10.3/admin_manual/maintenance/upgrade.html',
		],
		// 10.3.2 is the most recent with PHP 7.0 support. So 10.1.1 - 10.3.1 should always update through it
		'10.3' => [
			'latest' => '10.3.2',
			'web' => 'https://doc.owncloud.org/server/10.3/admin_manual/maintenance/upgrade.html',
		],
		'10.2' => [
			'latest' => '10.3.2',
			'web' => 'https://doc.owncloud.org/server/10.2/admin_manual/maintenance/upgrade.html',
		],
		'10.1' => [
			'latest' => '10.3.2',
			'web' => 'https://doc.owncloud.org/server/10.1/admin_manual/maintenance/upgrade.html',
		],
		// 10.1.1 is the most recent release with PHP 5.6 support
		// so we always offer it first for older versions to allow environment update at least to PHP 7.0
		'10.0' => [
			'latest' => '10.1.1',
			'web' => 'https://doc.owncloud.org/server/10.0/admin_manual/maintenance/upgrade.html',
		],
		'9.2.100' => [
			'latest' => '10.1.1',
			'web' => 'https://doc.owncloud.org/server/9.1/admin_manual/maintenance/upgrade.html',
		],
		'9.1.100' => [
			'latest' => '10.1.1',
			'web' => 'https://doc.owncloud.org/server/9.1/admin_manual/maintenance/upgrade.html',
		],
		'9.1.8' => [
			'latest' => '10.1.1',
			'web' => 'https://doc.owncloud.org/server/9.1/admin_manual/maintenance/upgrade.html',
		],
		'9.1' => [
			'latest' => '9.1.8',
			'web' => 'https://doc.owncloud.org/server/9.1/admin_manual/maintenance/upgrade.html',
		],
		'9.0.100' => [
			'latest' => '9.1.8',
			'web' => 'https://doc.owncloud.org/server/9.1/admin_manual/maintenance/upgrade.html',
		],
		// START: Due do a bug in the updater we need to enforce the update order
		// see https://github.com/owncloud/administration-internal/issues/19
		'9.0.11' => [
			'latest' => '9.1.8',
			'web' => 'https://doc.owncloud.org/server/9.1/admin_manual/maintenance/upgrade.html',
		],
		'9.0' => [
			'latest' => '9.0.11',
			'web' => 'https://doc.owncloud.org/server/9.0/admin_manual/maintenance/upgrade.html',
		],
		'9.0.2' => [
			'latest' => '9.0.4',
			'web' => 'https://doc.owncloud.org/server/9.0/admin_manual/maintenance/upgrade.html',
		],
		'9.0.1' => [
			'latest' => '9.0.4',
			'web' => 'https://doc.owncloud.org/server/9.0/admin_manual/maintenance/upgrade.html',
		],
		'9.0.0' => [
			'latest' => '9.0.4',
			'web' => 'https://doc.owncloud.org/server/9.0/admin_manual/maintenance/upgrade.html',
		],
		// END:  Due do a bug in the updater we need to enforce the update order
		'8.2.100' => [
			'latest' => '9.0.11',
			'web' => 'https://doc.owncloud.org/server/9.0/admin_manual/maintenance/upgrade.html',
		],
		'8.2.11' => [
			'latest' => '9.0.11',
			'web' => 'https://doc.owncloud.org/server/9.0/admin_manual/maintenance/upgrade.html',
		],
		'8.2' => [
			'latest' => '8.2.11',
			'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
		],
		'8.1.100' => [
			'latest' => '8.2.11',
			'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
		],
		'8.1.12' => [
			'latest' => '8.2.11',
			'web' => 'https://doc.owncloud.org/server/8.2/admin_manual/maintenance/upgrade.html',
		],
		'8.1' => [
			'latest' => '8.1.12',
			'web' => 'https://doc.owncloud.org/server/8.1/admin_manual/maintenance/upgrade.html',
		],
		'8.0.100' => [
			'latest' => '8.1.12',
			'web' => 'https://doc.owncloud.org/server/8.1/admin_manual/maintenance/upgrade.html',
		],
		'8.0.16' => [
			'latest' => '8.1.12',
			'web' => 'https://doc.owncloud.org/server/8.1/admin_manual/maintenance/upgrade.html',
		],
		'8.0' => [
			'latest' => '8.0.16',
			'web' => 'https://doc.owncloud.org/server/8.0/admin_manual/maintenance/upgrade.html',
		],
		'7.0.100' => [
			'latest' => '8.0.16',
			'web' => 'https://doc.owncloud.org/server/8.0/admin_manual/maintenance/upgrade.html',
		],
		'7.0.15' => [
			'latest' => '8.0.16',
			'web' => 'https://doc.owncloud.org/server/8.0/admin_manual/maintenance/upgrade.html',
		],
		'7' => [
			'latest' => '7.0.15',
			'web' => 'https://doc.owncloud.org/server/8.0/admin_manual/maintenance/upgrade.html',
		],
		'6' => [
			'latest' => '7.0.15',
			'web' => 'https://doc.owncloud.org/server/7.0/admin_manual/maintenance/upgrade.html',
		],
	],
	'eol_latest' => '10.7.100',
];
