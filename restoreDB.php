<?php

require_once 'config.php';
require_once 'functions.php';

$dao = new Dao();
$pdo = $dao->getPdo();

$query = 'UPDATE task SET progression=0, difficulty=NULL, closedDate=NULL';
$stmt = $pdo->prepare ( $query );
$stmt->execute();

$query = 'DELETE FROM userjob_task';
$stmt = $pdo->prepare ( $query );
$stmt->execute();
