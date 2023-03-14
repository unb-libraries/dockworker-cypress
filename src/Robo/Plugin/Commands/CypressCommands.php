<?php

namespace Dockworker\Robo\Plugin\Commands;

use Dockworker\Robo\Task\Docker\Tasks;
use \Dockworker\DockworkerException;

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
   * Install NPM packages.
   *
   * @hook pre-command tests:einbaum
   */
  public function installNpmDependencies() {
    if (!file_exists('node_modules') && file_exists('package-lock.json')) {
      $cmd = "npm ci";
    }
    elseif (!file_exists('package-lock.json')) {
      $cmd = "npm install";
    }
    else {
      $cmd = "npm update";
    }

    $this->_exec($cmd);
  }

  /**
   * Run all einbaum tests.
   *
   * @command tests:einbaum
   * @aliases einbaum
   * @e2e
   *
   * @option bool $gui
   *   Set to TRUE to use desktop GUI client. FALSE to run through CLI.
   *
   * @throws \Dockworker\DockworkerException
   */
  public function runEinbaumTests(bool $gui = FALSE) {
    $cmd = "npx einbaum --project-root=./tests/einbaum";
    if (!$gui) {
      $cmd .= " --headless";
    }

    try {
      $result = $this->_exec($cmd);
      if ($result->getExitCode() == 0) {
        $this->writeln("All specs passed.");
      }
      else {
        $this->writeln("Failing spec(s) detected.");
      }
    }
    catch (\Exception $e) {
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
