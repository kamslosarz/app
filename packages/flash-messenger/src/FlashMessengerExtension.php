<?php

namespace FlashMessenger;

use View\ViewExtension\ViewExtension;

class FlashMessengerExtension extends ViewExtension
{
    protected FlashMessenger $flashMessenger;

    public function __construct(FlashMessenger & $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

    protected function getFunctions(): array
    {
        return [
            'flashMessages' => [
                [&$this, 'flashMessages']
            ],
            'hasMessagesToFlash' => [
                [&$this, 'hasMessagesToFlash']
            ]
        ];
    }

    public function flashMessages(): array
    {
        return $this->flashMessenger->flash();
    }

    public function hasMessagesToFlash(): bool
    {
        return $this->flashMessenger->hasMessages();
    }
}