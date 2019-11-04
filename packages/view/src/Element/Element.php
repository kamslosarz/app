<?php

namespace View\Element;

use Collection\Collection;
use EventManager\Event\Event;
use EventManager\EventDispatcher;
use EventManager\EventManagerException;
use Exception;
use View\ViewException;
use View\ViewExtension\ExtensionEventManager;

class Element extends Collection
{
    protected $resourceFile;
    protected $oldPath = null;
    protected $resourcesPaths = [];
    protected $eventManager;

    /**
     * Element constructor.
     * @param string $resourceFile
     * @param array $resourcesPaths
     * @param array $parameters
     * @param ExtensionEventManager $eventManager
     */
    public function __construct(
        string $resourceFile,
        array $resourcesPaths,
        array $parameters,
        ExtensionEventManager $eventManager)
    {
        $this->resourceFile = $resourceFile;
        $this->resourcesPaths = implode(PATH_SEPARATOR, $resourcesPaths);
        $this->eventManager = $eventManager;

        parent::__construct($parameters);
    }

    /**
     * @param $key
     * @return mixed|string|null
     * @throws EventManagerException
     * @throws ViewException
     */
    public function __get($key)
    {
        if($this->has($key))
        {
            return $this->get($key);
        }
        elseif($this->eventManager->hasListener($key))
        {
            $event = new Event($key, new ViewContext());
            $eventDispatcher = new EventDispatcher($this->eventManager, $event);
            $eventDispatcher->dispatch();
            $results = $event->getResults();

            return array_pop($results);
        }

        throw new ViewException(sprintf('Variable \'%s\'not found', $key));
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return string
     * @throws EventManagerException
     * @throws ViewException
     */
    public function __call(string $name, array $arguments)
    {
        if($this->eventManager->hasListener($name))
        {
            $event = new Event($name, new ViewContext($arguments));
            $eventDispatcher = new EventDispatcher($this->eventManager, $event);
            $eventDispatcher->dispatch();
            $results = $event->getResults();

            return array_pop($results);
        }

        throw new ViewException(sprintf('Method \'%s\'not found', $name));
    }

    /**
     * @return string
     * @throws ViewException
     */
    public function __invoke(): string
    {
        ob_start();
        try
        {
            extract($this->__toArray());
            $this->setIncludePath();
            if(!stream_resolve_include_path($this->resourceFile))
            {
                throw new ViewException(sprintf('File \'%s\' not exists in \'%s\'', $this->resourceFile, $this->resourcesPaths));
            }
            include $this->resourceFile;
            $this->resetIncludePath();;
        }
        catch(Exception $exception)
        {
            ob_end_clean();

            throw new ViewException($exception);
        }

        return ob_get_clean();
    }

    /**
     * @throws ViewException
     */
    private function setIncludePath(): void
    {
        $this->oldPath = set_include_path($this->resourcesPaths);
        if(!$this->oldPath)
        {
            throw new ViewException(sprintf('Invalid resource path \'%s\'', $this->resourcesPaths));
        }
    }

    private function resetIncludePath(): void
    {
        set_include_path($this->oldPath);
    }
}