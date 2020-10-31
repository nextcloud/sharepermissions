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

use OC\Files\Cache\Wrapper\CacheWrapper as Wrapper;
use OCP\Constants;
use OCP\IUser;

class CacheWrapper extends Wrapper {

	/** @var ?IUser */
	private $user;

	/** @var Manager */
	private $manager;

	public function __construct($cache, ?IUser $user, Manager $manager) {
		parent::__construct($cache);

		$this->user = $user;
		$this->manager = $manager;
	}


	protected function formatCacheEntry($entry) {
		$entry = parent::formatCacheEntry($entry);

		if ($this->manager->filterSharePermissions($this->user)) {
			$entry['permissions'] &= ~Constants::PERMISSION_SHARE;
		}

		return $entry;
	}


}
