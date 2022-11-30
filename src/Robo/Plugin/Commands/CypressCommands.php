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
    $this->setRunOtherCommand('cypress:up');
    $this->taskDockerLogs($this->getContainerId(), '-f', '-n 0')
      ->run();
  }

  /**
   * Start the cypress container.
   *
   * @command cypress:up
   *
   * @option string|null $service
   *   Name of the cypress container. Defaults to the name of the primary
   *   service container, prefixed by "cypress".
   * @option bool $forceRecreate
   *   Same as docker-compose's --force-recreate.
   * @option bool $noDeps
   *   Same as docker-compose's --no-deps.
   */
  public function up($service = 'cypress', bool $forceRecreate = FALSE, bool $noDeps = FALSE) {
    $this->io()->title("Starting cypress container");

    $cmd = 'docker-compose up -d';
    if ($forceRecreate) {
      $cmd .= ' --force-recreate';
    }
    if ($noDeps) {
      $cmd .= ' --no-deps';
    }
    $cmd .= $service;

    $this->_exec($cmd);
  }

}
