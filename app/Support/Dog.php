<?php

namespace Learn;

class Dog extends Animal implements Speakable {
    public function move() {
        return "{$this->name} 在奔跑";
    }

    public function speak() {
        return "汪汪！";
    }
}
