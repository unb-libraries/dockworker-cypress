<?php

namespace Dockworker\Robo\Task\Docker;

/**
 * Provide additional Docker tasks.
 */
trait Tasks {

  /**
   * Retrieve container logs.
   *
   * @param string|\Robo\Task\Docker\Result $cidOrResult
   * @param string ...$options
   *  Any number of string arguments to pass as command parameters.
   *
   * @return \Robo\Collection\CollectionBuilder
   */
  protected function taskDockerLogs($cidOrResult, ...$options) {
    $args = implode(' ', $options);
    return $this->task(Log::class, "$args $cidOrResult");
  }

}
