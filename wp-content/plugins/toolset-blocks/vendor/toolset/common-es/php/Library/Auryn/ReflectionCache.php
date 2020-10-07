<?php

namespace ToolsetCommonEs\Library\Auryn;

interface ReflectionCache
{
    public function fetch($key);
    public function store($key, $data);
}
