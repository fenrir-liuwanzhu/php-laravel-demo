<?php

namespace Learn;

class User {
    public $name;        // 公开，外部可直接访问
    private $age;        // 私有，外部只能通过方法拿

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age  = $age;
    }

    public function greet() {
        return "你好，我是 {$this->name}";
    }

    public function getAge() {
        return $this->age;   // 类内部能访问 private
    }
}
