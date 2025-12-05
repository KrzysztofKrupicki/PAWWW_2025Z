<?php
/**
 * Plik showpage.php
 * Odpowiada za pobieranie treści podstron z bazy danych.
 */

include("cfg.php");

/**
 * Funkcja pobierająca treść podstrony z bazy danych
 * @param int $id ID podstrony do pobrania
 * @return string Treść podstrony lub komunikat o błędzie
 */
function PokazPodstrone($id)
{
    // Zabezpieczenie przed SQL Injection
    $id_clear = intval($id);
    global $link;

    // Pobranie podstrony z bazy danych
    $qry = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $qry) or die(mysqli_error($link));
    $row = mysqli_fetch_array($result);

    // Sprawdzenie czy strona istnieje
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }
    return $web;
}
?>