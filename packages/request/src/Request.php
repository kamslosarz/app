<?php

namespace Request;

use Collection\Collection;

class Request
{
    private $parameters;

    public function __construct()
    {
        $this->startSession();
        $this->parameters = $this->getParametersCollection();
    }

    public function getQuery(): Collection
    {
        return $this->parameters->get('get');
    }

    public function getPost(): Collection
    {
        return $this->parameters->get('post');
    }

    public function getServer(): Collection
    {
        return $this->parameters->get('server');
    }

    public function getCookie(): Collection
    {
        return $this->parameters->get('cookie');
    }

    public function getSession(): Collection
    {
        return $this->parameters->get('session');
    }

    /**
     * @return Collection
     */
    private function getParametersCollection(): Collection
    {
        $collection = new Collection();
        $collection->set('get', new Collection($_GET));
        $collection->set('post', new Collection($_POST));
        $collection->set('server', new Collection($_SERVER));
        $collection->set('cookie', new Collection($_COOKIE));
        $collection->set('session', new Collection($_SESSION));

        return $collection;
    }

    private function startSession(): void
    {
        session_start();
    }

    public function getRequestUri(): string
    {
        return $this->getServer()->get('REQUEST_URI', '/');
    }

    public function getRequestMethod(): string
    {
        return $this->getServer()->get('REQUEST_METHOD', 'GET');
    }

    public function saveSessionAndCookie(): void
    {
        $_COOKIE = $this->getCookie()->__toArray();
        $_SESSION = $this->getSession()->__toArray();
    }
}