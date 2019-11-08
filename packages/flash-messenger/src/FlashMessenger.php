<?php

namespace FlashMessenger;

use Request\Request;

class FlashMessenger
{
    const TYPE_WARNING = 'WARNING';
    const TYPE_INFO = 'INFO';
    const TYPE_SUCCESS = 'SUCCESS';
    const TYPE_ERROR = 'ERROR';

    static array $types = [
        'MESSAGES_' . self::TYPE_WARNING,
        'MESSAGES_' . self::TYPE_SUCCESS,
        'MESSAGES_' . self::TYPE_ERROR,
        'MESSAGES_' . self::TYPE_INFO,
    ];

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function add(string $content, string $type): self
    {
        $this->request->getSession()->add($this->getType($type), $content);

        return $this;
    }

    public function get(string $type): array
    {
        return $this->request->getSession()->get($this->getType($type), []);
    }

    public function flash(): array
    {
        $messages = [];

        foreach(self::$types as $type)
        {
            if($this->request->getSession()->has($type))
            {
                $messages[$type] = $this->request->getSession()->get($type);
                $this->request->getSession()->remove($type);
            }
        }

        return $messages;
    }

    private function getType(string $type): string
    {
        if(in_array($type, [
            self::TYPE_ERROR,
            self::TYPE_WARNING,
            self::TYPE_INFO,
            self::TYPE_SUCCESS,
        ]))
        {
            return sprintf('MESSAGES_%s', $type);
        }

        return 'MESSAGES_INFO';
    }

    public function hasMessages(): bool
    {
        foreach(self::$types as $type)
        {
            if($this->request->getSession()->has($type))
            {
                return true;
            }
        }

        return false;
    }
}