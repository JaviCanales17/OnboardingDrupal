<?php

namespace Drupal\libro\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\user\UserInterface;

class UserLoginEvent extends Event {
  public const LOGIN = 'libro.user_login';

  protected UserInterface $account;

  public function __construct(UserInterface $account) {
    $this->account = $account;
  }

  public function getAccount(): UserInterface {
    return $this->account;
  }
}