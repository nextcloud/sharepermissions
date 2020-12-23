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

namespace OCA\SharePermissions;

use OCP\AppFramework\Services\IAppConfig;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\IUser;

class Manager {
	public const MODE_ALLOW = 0;
	public const MODE_BLOCK = 1;

	/** @var IConfig */
	private $config;

	/** @var IGroupManager */
	private $groupManager;

	/** @var bool */
	private $filterShareCache = [];

	public function __construct(IAppConfig $config, IGroupManager $groupManager) {
		$this->config = $config;
		$this->groupManager = $groupManager;
	}

	public function getMode(): int {
		return (int)$this->config->getAppValue('mode', (string)self::MODE_BLOCK);
	}

	public function setMode(int $mode): void {
		if ($mode < 0 || $mode > 1) {
			throw new \RuntimeException('Invalid mode supplied');
		}

		$this->config->setAppValue('mode', (string)$mode);
	}

	public function getGroups(): \Iterator {
		$groups = $this->config->getAppValue('groups', '[]');
		$groups = json_decode($groups, true);

		foreach ($groups as $group) {
			$group = $this->groupManager->get($group);
			if ($group === null) {
				continue;
			}

			yield $group;
		}
	}

	public function addGroup(string $groupId): bool {
		$group = $this->groupManager->get($groupId);

		if ($group === null) {
			return false;
		}

		$groups = $this->config->getAppValue('groups', '[]');
		$groups = json_decode($groups, true);

		$groups[] = $groupId;

		$groups = array_unique(array_values($groups));

		$this->config->setAppValue('groups', json_encode($groups));
		return true;
	}

	public function removeGroup(string $groupId): bool {
		$group = $this->groupManager->get($groupId);

		if ($group === null) {
			return false;
		}

		$groups = $this->config->getAppValue('groups', '[]');
		$groups = json_decode($groups, true);

		$groups = array_diff($groups, [$groupId]);
		$this->config->setAppValue('groups', json_encode($groups));
		return true;
	}

	public function filterSharePermissions(?IUser $user): bool {
		if ($user === null) {
			return false;
		}

		if (!isset($this->filterShareCache[$user->getUID()])) {
			$groups = $this->config->getAppValue('groups', '[]');
			$groups = json_decode($groups, true);

			$userGroups = $this->groupManager->getUserGroupIds($user);

			if (array_intersect($groups, $userGroups) === []) {
				$res = $this->getMode() === self::MODE_ALLOW;
			} else {
				$res = $this->getMode() === self::MODE_BLOCK;
			}

			$this->filterShareCache[$user->getUID()] = $res;
		}

		return $this->filterShareCache[$user->getUID()];
	}
}
