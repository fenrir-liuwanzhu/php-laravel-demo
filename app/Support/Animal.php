<?php

namespace Learn;

abstract class Animal {
    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function name() {
        return $this->name;
    }

    abstract public function move();
}