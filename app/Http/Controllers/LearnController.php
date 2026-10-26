<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LearnController extends Controller
{
    public function index()
    {
        echo "Hello World<br>";
        echo 2026, "<br>";

        $name = "John";
        $age = 20;
        $email = "john@example.com";
        $phone = "1234567890";
        $address = "123 Main St, Anytown, USA";
        $city = "Anytown";
        $state = "CA";
        $zip = "12345";
        $country = "USA";

        echo "Name: $name<br>";
        echo $name . " is " . $age . " years old<br>";

        $array = [1, 2, 3, 4, 5];
        var_dump($array);
        print_r($array);
        echo "<br>";
        echo $array[2], "<br>";
        echo count($array), "<br>";

        foreach ($array as $item) {
            echo $item, "<br>";
        }

        $array2 = [
            "name" => "John",
            "age" => 20,
            "email" => "john@example.com",
            "phone" => "1234567890",
            "address" => "123 Main St, Anytown, USA",
        ];

        echo $array2["name"], "<br>";

        foreach ($array2 as $key => $value) {
            echo $key, ": ", $value, "<br>";
        }

        function add($a, $b) {
            return $a + $b;
        }

        echo add(1, 2), "<br>";

        $student = [
            "name" => "John",
            "age" => 20,
            "scoress" => [90, 80, 70, 60, 50],
        ];

        $total = 0;
        foreach ($student["scoress"] as $score) {
            $total += $score;
        }

        echo $total, "<br>";

        $average = $total / count($student["scoress"]);

        echo $student["name"] . "的" . count($student["scoress"]) . "门课的平均分是" . $average, "<br>";

        
    }

    public function day2()
    {
        $age = 20;
        if ($age >= 18) {
            echo "成年";
        } else if ($age >= 12) {
            echo "青少年";
        } else {
            echo "儿童";
        }
        echo "<br>";

        $score = 90;
        switch ($score) {
            case 90: $output = "优秀"; break;
            case 80: $output = "良好"; break;
            case 70: $output = "中等"; break;
            case 60: $output = "及格"; break;
            default: $output = "不及格"; break;
        }

        echo $output, "<br>";

        $str = "Hello World";
        echo strlen($str), "<br>";
        echo strtoupper($str), "<br>";
        echo strtolower($str), "<br>";
        echo substr($str, 0, 7), "<br>";
        echo str_replace("Hello", "Hi", $str), "<br>";
        echo $str, "------------------------------------------------<br>";
        print_r(explode(" ", $str));
        echo "<br>";
        echo implode("123 ", explode(" ", $str)), "<br>";


        $arr = [2, 3, 4, 5, 1];
        echo count($arr), "<br>";
        echo in_array(3, $arr), "<br>";
        echo array_search(3, $arr), "<br>";
        echo array_sum($arr), "<br>";
        echo array_product($arr), "<br>";
        echo sort($arr), "<br>";
        print_r($arr);
        echo "<br>";
        echo array_shift($arr), "<br>";
        print_r($arr);
        echo "<br>";
        echo array_push($arr, 6), "<br>";
        print_r($arr);
        echo "<br>";
        echo array_pop($arr), "<br>";

        $name = $_GET['name'] ?? '未传';   // ?? 是空合并运算符（≈ JS 的 ??）
        $age  = $_GET['age'] ?? '未传';
        echo "GET 参数 name={$name}, age={$age}", "<br>";
        
    }

    public function day3()
    {
        $output = '';

        // ① 函数作用域
        $output .= "当前请求方法: " . $_SERVER['REQUEST_METHOD'] . "\n";
        $output .= "浏览器UA: " . ($_SERVER['HTTP_USER_AGENT'] ?? '未知') . "\n";

        function addScope($a, $b) {
            return $a + $b;
        }
        $output .= "传参调用结果: " . addScope(3, 4) . "\n";

        function counter() {
            static $count = 0;
            $count++;
            return $count;
        }
        $output .= "counter: " . counter() . ", " . counter() . ", " . counter() . "\n";

        // ② 处理 POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '未填';
            $output .= "POST 收到用户名: {$username}\n";
        }

        // 返回：上半部分是输出，下半部分是一个 POST 表单（提交给自己）
        return response(
            '<pre>' . htmlspecialchars($output) . '</pre>' .
            '<form method="POST" action="/learn3">' .
            ' "'. csrf_token() . '"' .
            '<input type="hidden" name="_token" value="' . csrf_token() . '">' .
            '<input name="username" placeholder="输入用户名">' .
            '<button type="submit">POST 提交</button>' .
            '</form>'
        );
    }

    public function day4()
    {
        require_once app_path('Support/helpers_learn.php');
        echo \Learn\add(1, 2), "<br>";
        echo \Learn\greet("John"), "<br>";

        // 当前控制器自己的命名空间（就是顶部那行 App\Http\Controllers）
        echo "当前文件命名空间: " . (__NAMESPACE__ ?: '全局') . "<br>";
    }
}
