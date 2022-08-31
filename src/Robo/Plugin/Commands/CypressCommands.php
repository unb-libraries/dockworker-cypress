<?php

namespace Dockworker\Robo\Plugin\Commands;

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
   * @throws \Dockworker\DockworkerException
   */
  public function runCypressTests() {
    $this->io()->title("Running Cypress Tests");
    $this->taskDockerStart($this->getContainerId())
      ->run();
  }

}
