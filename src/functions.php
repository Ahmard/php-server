<?php

namespace PHPServer;

function base_path(string|null $path): string
{
    return dirname(__DIR__) . "/$path";
}