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

use OC\Files\Storage\Wrapper\Wrapper;
use OCP\Constants;
use OCP\IUser;

class StorageWrapper extends Wrapper {

	/** @var ?IUser */
	private $user;

	/** @var Manager */
	private $manager;

	public function __construct($parameters) {
		parent::__construct($parameters);

		$this->user = $parameters['loggedInUser'];
		$this->manager = $parameters['manager'];
	}

	public function isSharable($path) {
		if ($this->manager->filterSharePermissions($this->user)) {
			return false;
		} else {
			return parent::isSharable($path);
		}
	}

	public function getPermissions($path) {
		$permssions = parent::getPermissions($path);

		if ($this->manager->filterSharePermissions($this->user)) {
			$permssions &= ~Constants::PERMISSION_SHARE;
		}

		return $permssions;
	}

	public function getCache($path = '', $storage = null) {
		if (!$storage) {
			$storage = $this;
		}
		$cache = $this->storage->getCache($path, $storage);
		return new CacheWrapper($cache, $this->user, $this->manager);

	}

}
