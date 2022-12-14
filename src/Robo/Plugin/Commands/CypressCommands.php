<?php

namespace Dockworker\Robo\Plugin\Commands;

use Dockworker\Robo\Task\Docker\Tasks;

/**
 * Defines commands used to run Cypress tests.
 */
class CypressCommands extends DockworkerCommands {

  use Tasks;

  protected $containerId;

  protected function getContainerId() {
    if (!isset($this->containerId)) {
      $this->containerId = "cypress.$this->instanceName";
    }
    return $this->containerId;
  }

  /**
   * Run all Cypress tests.
   *
   * @hook post-command tests:all
   * @throws \Dockworker\DockworkerException
   */
  public function addCypressTests() {
    $this->setRunOtherCommand('tests:cypress');
  }

  /**
   * Run all Cypress tests.
   *
   * @command tests:cypress
   * @aliases cypress
   * @e2e
   * @throws \Dockworker\DockworkerException
   */
  public function runCypressTests() {
    try {
      $this->setRunOtherCommand('cypress:up', 'Failing spec(s) detected.');
      $this->writeln("All specs passed.");
    }
    catch (DockworkerException $e) {
      $this->say($e->getMessage());
    }
  }

  /**
   * Start the cypress container.
   *
   * @command cypress:up
   *
   * @param string $service
   *   Name of the cypress container. Defaults to the name of the primary
   *   service container, prefixed by "cypress".
   * @option bool $forceRecreate
   *   Same as docker-compose's --force-recreate.
   * @option bool $noDeps
   *   Same as docker-compose's --no-deps.
   */
  public function up(string $service = 'cypress', bool $forceRecreate = FALSE, bool $noDeps = FALSE) {
    $this->io()->title("Starting cypress container");

    $cmd = "docker-compose up --abort-on-container-exit --exit-code-from $service";
    if ($forceRecreate) {
      $cmd .= ' --force-recreate';
    }
    if ($noDeps) {
      $cmd .= ' --no-deps';
    }
    $cmd .= " $service";

    return $this->_exec($cmd);
  }

}
