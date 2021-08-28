<?php
// $items = array("яблоко", "груша", "вишня");
// $items = ["яблоко", 
// "груша",
//  "вишня" =>
//  ["сладкая вишня", "кислая вишня"],
// ];
$items = [
    "apple" => "яблоко",
    "pear" => "груша",
    "cherry" => "вишня",
];
// echo $items["apple"];
// $counter = 0;
// $number = 1;
// while ($counter < count($items)) {
//     echo  "$number) {$items[$counter]} <br>";
//     $counter ++;
//     $number ++;
// }
// for ($counter = 0, $number = 1; $counter < count($items); $counter ++, $number = $counter + 1) {
//     echo "{$number}) {$items[$counter]}<br/>";
// }

$fruits = [
    "apple" => [
        "name" => "яблоко", 
        "price" => 100, 
        "color" => "red", 
        "quantity" => 8,
    ],
    "pear" => [
        "name" => "груша", 
        "price" => 50, 
        "color" => "yellow", 
        "quantity" => 56,
    ],
    "cherry" => [
        "name" => "вишня", 
        "price" => 39, 
        "color" => "brown", 
        "quantity" => 96,
    ],
];


function printCode($code) {
    echo "<pre>";
    print_r($code);
    echo "</pre>";
};
// printCode($fruits);

foreach($fruits as $keyFruit => $oneFruit) {
    foreach($oneFruit as $keyFruitParam => $oneFruitParam)
    printCode($oneFruitParam);
}



