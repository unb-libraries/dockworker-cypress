<?php

namespace Dockworker\Robo\Task\Docker;

use Robo\Task\Docker\Base;
use Robo\Task\Docker\Result;

/**
 * Retrieves Docker container logs.
 *
 * ```php
 * <?php
 * $this->taskDockerLog($cidOrResult, '-f')
 *      ->run();
 * ?>
 * ```
 */
class Log extends Base {

  /**
   * The command string.
   *
   * @var string
   */
  protected $command = "docker logs";

  /**
   * The container ID.
   *
   * @var null|string
   */
  protected $cid;

  /**
   * @param string|\Robo\Task\Docker\Result $cidOrResult
   */
  public function __construct($cidOrResult)
  {
    $this->cid = $cidOrResult instanceof Result ? $cidOrResult->getCid() : $cidOrResult;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommand()
  {
    return $this->command . ' ' . $this->arguments . ' ' . $this->cid;
  }


}
