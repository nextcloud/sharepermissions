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

namespace OCA\SharePermissions\Command;

use OCA\SharePermissions\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Show extends Command {

	/** @var Manager */
	private $manager;

	public function __construct(Manager $manager) {
		parent::__construct();

		$this->manager = $manager;
	}

	/**
	 * @return void
	 */
	protected function configure() {
		$this->setName('sharepermissions:show');
		$this->setDescription('Show the current mode and groups listed for share permissions');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		if ($this->manager->getMode() === Manager::MODE_ALLOW) {
			$output->writeln("<info>The following groups are ALLOWED to share</info>");
		} else {
			$output->writeln("<info>The following groups are BLOCKED from sharing</info>");
		}
		$output->writeln('');

		// Output all the groups
		$groups = $this->manager->getGroups();

		foreach ($groups as $group) {
			$output->writeln("<info>- " . $group->getGID() . "</info>");
		}

		return 0;
	}
}
