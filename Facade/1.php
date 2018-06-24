<?php

//  В основе Facade лежит простая идея, это создание одной точки входа для клиента

// Т.е. вместо использования такого подхода:
$productLine = parseText('text.txt');
$productId = getProductId($productLine);
$productName = getProductName($productLine);
$savedProduct = seveProductInDb($productId, $productName);

// Нужно сделать такое:
$facade = new ProductFacade('text.txt');
$facade->saveProduct();