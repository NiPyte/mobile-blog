<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development DB
$db['dsn'] = 'mysql:host=localhost;dbname=mobile_blog_tests';
return $db;