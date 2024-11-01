<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function isSession() {
    if(!isset($_SESSION)) {
        session_start();
    }
}

function esUltimo(string $actual, string $proximo) :bool {
    if($actual !== $proximo) {
        return true;
    }
    return false;
}

// REVISA SI EL USUARIO ESTÁ AUTENTICADO
function isAuth() {
    if(!isset($_SESSION["login"])) {
        header('location: /');
    }
}

function isAdmin() {
    if(!isset($_SESSION['admin'])) {
        header('location: /');
    }
}