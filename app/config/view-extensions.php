<?php

use App\ViewExtension\AssetExtension;
use App\ViewExtension\FlashMessengerExtension;
use Container\Process\ProcessContext;
use View\View;
use View\ViewExtension\Extension\IncludeExtension;

return [
    AssetExtension::class => [],
    IncludeExtension::class => function (View $view, ProcessContext $processContext) {
        return [
            $view
        ];
    },
    FlashMessengerExtension::class => function (View $view, ProcessContext $processContext) {
        $request = $processContext->get('request');

        return [
            new FlashMessenger\FlashMessenger($request)
        ];
    }
];