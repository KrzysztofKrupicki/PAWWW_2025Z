<?php
/**
 * Plik główny index.php
 * Odpowiada za routing i wyświetlanie odpowiednich podstron.
 */

include("cfg.php");
include("showpage.php");

// Raportowanie błędów - wyłączenie notice i warning dla czystszego outputu
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Domyślna strona
$page = 1;
$title = 'Największe mosty świata';
$headerTitle = 'Największe Mosty Świata';
$headerDescription = 'Największe mosty świata to imponujące osiągnięcia inżynierii, które zachwycają długością, wysokością i architekturą.';
$pageContent = PokazPodstrone(1);

if (isset($_GET['page'])) {
    // Zabezpieczenie przed SQL Injection
    $page_id = intval($_GET['page']);
    if ($page_id > 0) {
        $page = $page_id;
    }

    switch ($page) {
        case 1:
            $title = 'Największe mosty świata';
            $headerTitle = 'Największe Mosty Świata';
            $headerDescription = 'Największe mosty świata to imponujące osiągnięcia inżynierii, które zachwycają długością, wysokością i architekturą.';
            $pageContent = PokazPodstrone(1);
            break;
        case 2:
            $title = 'Największe mosty świata - o stronie';
            $headerTitle = 'O projekcie';
            $headerDescription = 'Witryna prezentuje największe i najciekawsze mosty świata, ich typy, historię oraz ciekawostki inżynieryjne.';
            $pageContent = PokazPodstrone(2);
            break;
        case 3:
            $title = 'Największe mosty świata - typy mostów';
            $headerTitle = 'Największe Mosty Świata';
            $headerDescription = 'Największe mosty świata to imponujące osiągnięcia inżynierii, które zachwycają długością, wysokością i architekturą.';
            $pageContent = PokazPodstrone(3);
            break;
        case 4:
            $title = 'Największe mosty świata - galeria';
            $headerTitle = 'Galeria Największych Mostów Świata';
            $headerDescription = 'Odkryj zdjęcia i ciekawostki o najdłuższych i najbardziej imponujących mostach na świecie.';
            $pageContent = PokazPodstrone(4);
            break;
        case 5:
            $title = 'Największe mosty świata - kontakt';
            $headerTitle = 'Największe Mosty Świata';
            $headerDescription = 'Skontaktuj się z nami, jeśli masz pytania lub chcesz podzielić się ciekawostką o mostach!';
            $pageContent = PokazPodstrone(5);
            break;
        case 6:
            $title = 'Największe mosty świata - skrypty Javascript';
            $headerTitle = 'Skrypty JavaScript';
            $headerDescription = 'Laboratorium 2.';
            $pageContent = PokazPodstrone(6);
            break;
        case 7:
            $title = 'Największe mosty świata - jQuery';
            $headerTitle = 'jQuery';
            $headerDescription = 'Laboratorium 3.';
            $pageContent = PokazPodstrone(7);
            break;
        case 8:
            $title = 'Największe mosty świata - PHP';
            $headerTitle = 'PHP';
            $headerDescription = 'Laboratorium 4.';
            $pageContent = PokazPodstrone(8);
            break;
        case 9:
            $title = 'Największe mosty świata - Filmy o mostach';
            $headerTitle = 'Filmy o mostach';
            $headerDescription = 'Odkryj fascynujące filmy dokumentalne i edukacyjne o największych mostach świata, ich konstrukcji i historii.';
            $pageContent = PokazPodstrone(9);
            break;
        default:
            $title = '404 Strona nie istnieje';
            $headerTitle = 'Błąd 404';
            $headerDescription = 'Strona, której szukasz, nie została znaleziona.';
            $pageContent = PokazPodstrone(10);
            break;
    }
}

/**
 * Funkcja renderująca treść strony
 * Obsługuje zarówno czysty HTML jak i kod PHP w treści strony.
 */
function renderPage($content)
{
    // sprawdzamy, czy w treści jest kod PHP
    if (strpos($content, '<?php') !== false) {
        // tworzymy tymczasowy plik i include
        $tmp_file = tempnam(sys_get_temp_dir(), 'page_') . '.php';
        file_put_contents($tmp_file, $content);
        include $tmp_file;
        unlink($tmp_file);
    } else {
        // zwykły HTML
        echo $content;
    }
}
?>
<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Krzysztof Krupicki" />
    <meta name="description" content="Projekt 1. Strona o największych mostach świata, ich typach i ciekawostkach." />
    <meta name="keywords"
        content="HTML5, CSS3, Javascript, mosty, inżynieria, architektura, konstrukcje, długie mosty, wysokie mosty, największe mosty świata" />
    <link rel="stylesheet" href="./css/style.css">
    <?php
    if (isset($_GET['page']) && intval($_GET['page']) == 7) {
        echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'></script>";
    }
    ?>
    <title><?php echo $title; ?></title>
</head>

<body<?php if (isset($_GET['page']) && intval($_GET['page']) == 6)
    echo " onload='startClock()'"; ?>>
    <header>
        <img src="./images/logo.png" alt="Logo Mosty" class="logo" />
        <div class="logo-content">
            <h1><?php echo $headerTitle; ?></h1>
            <p><?php echo $headerDescription; ?></p>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="./?page=1">Strona główna</a></li>
            <li><a href="./?page=2">O stronie</a></li>
            <li><a href="./?page=3">Typy mostów</a></li>
            <li><a href="./?page=4">Galeria</a></li>
            <li><a href="./?page=5">Kontakt</a></li>
            <li><a href="./?page=6">Skrypty JS</a></li>
            <li><a href="./?page=7">jQuery</a></li>
            <li><a href="./?page=8">Lab 4 - PHP</a></li>
            <li><a href="./?page=9">Filmy</a></li>
        </ul>
    </nav>

    <main>
        <?php renderPage($pageContent); ?>
    </main>

    <?php
    $nr_indeksu = '175036';
    $nrGrupy = 'ISI2';
    ?>
    <footer>
        <p>&copy; 2025 Największe mosty świata - <?php echo "Krzysztof Krupicki $nr_indeksu, $nrGrupy" ?></p>
    </footer>

    <?php
    if (isset($_GET['page']) && intval($_GET['page']) == 6) {
        echo "<script src='./js/kolorujtlo.js'></script><script src='./js/timedate.js'></script>";
    }
    ?>
    </body>

</html>