<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2020, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\SharePermissions\AppInfo;

use OCA\SharePermissions\Manager;
use OCA\SharePermissions\StorageWrapper;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Files\Storage\IStorage;
use OCP\IUserSession;

class Application extends App implements IBootstrap {
	public const APP_ID = 'sharepermissions';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context): void {
		$this->setupWrapper();
	}

	public function setupWrapper() {
		\OC\Files\Filesystem::addStorageWrapper(
			'sharepermissions',
			function ($mountPoint, IStorage $storage) {
				/** @var IUserSession $userSession */
				$userSession = $this->getContainer()->get(IUserSession::class);
				$manager = $this->getContainer()->get(Manager::class);
				return new StorageWrapper([
					'storage' => $storage,
					'loggedInUser' => $userSession->getUser(),
					'manager' => $manager
				]);
			},
			-15
		);
	}
}
