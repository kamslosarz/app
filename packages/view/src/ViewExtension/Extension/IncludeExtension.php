<?php


namespace View\ViewExtension\Extension;


use View\View;
use View\ViewException;
use View\ViewExtension\ViewExtension;

class IncludeExtension extends ViewExtension
{
    /**
     * @var View
     */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    protected function getFunctions(): array
    {
        return [
            'include' => [
                [&$this, 'include']
            ]
        ];
    }

    /**
     * @param string $file
     * @param array $parameters
     * @param bool $noSpaces
     * @return string
     * @throws ViewException
     */
    public function include(string $file, array $parameters = [], bool $noSpaces = false): string
    {
        return $this->view->render($file, $parameters, $noSpaces);
    }
}