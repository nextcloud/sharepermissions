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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Mode extends Command {

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
		$this->setName('sharepermissions:mode');
		$this->setDescription('Change the permission mode (block or allow)');
		$this->addArgument('mode', InputArgument::REQUIRED, 'Mode to set, must be block or allow');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		$mode = $input->getArgument('mode');
		if ($mode === 'block') {
			$this->manager->setMode(Manager::MODE_BLOCK);
			$output->writeln("<info>The new mode is BLOCK</info>");
		} else if ($mode === 'allow') {
			$this->manager->setMode(Manager::MODE_ALLOW);
			$output->writeln("<info>The new mode is ALLOW</info>");
		} else {
			$output->writeln("<error>Please provide block or allow</error>");
			return -1;
		}

		return 0;
	}
}
