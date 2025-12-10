<?php

class Vehicle{
    private $motor = 1;
    private $body = 1;
    private $speed = 0;
    protected $wheel = 4;
    protected $mirror = 3;
    protected $acceleration = 1;


}

class Car extends Vehicle {
    public function __construct(){
    }
}
class Moto extends Vehicle {
    public function __construct(){
        $this->wheel = 2;
        $this->mirror = 2;
        $this->acceleration = 2;
    }
}

$myCar = new Car();
echo "<pre>";
print_r($myCar);
$myMoto= new Moto();
echo "<pre>";
print_r($myMoto);

