<?php
$arUrlRewrite=array (
  9 => 
  array (
    'CONDITION' => '#^/product-category/(.+)/(\\\\?(.*))?#',
    'RULE' => 'SECTION_CODE=$1&$2',
    'ID' => '',
    'PATH' => '/product-category/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/catalog/(.+)/(.+)/(\\\\?(.*))?#',
    'RULE' => 'SECTION_CODE=$1&ELEMENT_CODE=$2&$3',
    'ID' => '',
    'PATH' => '/catalog/detail.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/catalog-tag/(.+)/(\\\\?(.*))?#',
    'RULE' => 'TAGS=$1&$2',
    'ID' => '',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/blog/(.+)/(.+)/(\\\\?(.*))?#',
    'RULE' => 'SECTION_CODE=$1&ELEMENT_CODE=$2&3',
    'ID' => '',
    'PATH' => '/blog/detail.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/catalog/(.+)/(\\\\?(.*))?#',
    'RULE' => 'SECTION_CODE=$1&$2',
    'ID' => '',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/product/(.+)/(\\\\?(.*))?#',
    'RULE' => 'ELEMENT_CODE=$1&$2',
    'ID' => '',
    'PATH' => '/product/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/blog/(.+)/(\\\\?(.*))?#',
    'RULE' => 'SECTION_CODE=$1&2',
    'ID' => '',
    'PATH' => '/blog/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);
