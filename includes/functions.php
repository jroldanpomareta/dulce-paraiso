<?php
// includes/functions.php
require_once __DIR__ . '/db.php';

function isLoggedIn(){
    return !empty($_SESSION['user_id']);
}

function isAdmin(){
    return (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

function getUser(){
    if(!isLoggedIn()) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT id,nombre,email,role FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function redirect($url){
    header("Location: $url");
    exit;
}
