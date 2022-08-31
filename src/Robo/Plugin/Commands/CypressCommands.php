<?php

namespace Dockworker\Robo\Plugin\Commands;

/**
 * Defines commands used to run Cypress tests.
 */
class CypressCommands extends DockworkerCommands {

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
    $this->taskDockerStart('bnaldlibunbca_cypress_1')
      ->run();
  }

}
