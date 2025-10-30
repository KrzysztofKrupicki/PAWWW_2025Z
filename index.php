<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);


$page = './html/main.html';
if (isset($_GET['page'])) {
  switch ($_GET['page']) {
    case 'main':
      $page = './html/main.html';
      $title = 'Największe mosty świata';
      $headerTitle = 'Największe Mosty Świata';
      $headerDescription = 'Największe mosty świata to imponujące osiągnięcia inżynierii, które zachwycają długością, wysokością i
                architekturą.';
      break;
    case 'about':
      $page = './html/about.html';
      $title = 'Największe mosty świata - o stronie';
      $headerTitle = 'O projekcie';
      $headerDescription = 'Witryna prezentuje największe i najciekawsze mosty świata, ich typy, historię oraz ciekawostki
                inżynieryjne.';
      break;
    case 'bridge_types':
      $page = './html/bridge_types.html';
      $title = 'Największe mosty świata - typy mostów';
      $headerTitle = 'Największe Mosty Świata';
      $headerDescription = 'Największe mosty świata to imponujące osiągnięcia inżynierii, które zachwycają długością, wysokością i
                architekturą.';
      break;
    case 'gallery':
      $page = './html/gallery.html';
      $title = 'Największe mosty świata - galeria';
      $headerTitle = 'Galeria Największych Mostów Świata';
      $headerDescription = 'Odkryj zdjęcia i ciekawostki o najdłuższych i najbardziej imponujących mostach na świecie.';
      break;
    case 'contact':
      $page = './html/contact.html';
      $title = 'Największe mosty świata - kontakt';
      $headerTitle = 'Największe Mosty Świata';
      $headerDescription = 'Skontaktuj się z nami, jeśli masz pytania lub chcesz podzielić się ciekawostką o mostach!';
      break;
    case 'scripts':
      $page = './html/scripts.html';
      $title = 'Największe mosty świata - skrypty Javascript';
      $headerTitle = 'Skrypty JavaScript';
      $headerDescription = 'Laboratorium 2.';
      break;
    case 'jquery':
      $page = './html/jquery.html';
      $title = 'Największe mosty świata - jQuery';
      $headerTitle = 'jQuery';
      $headerDescription = 'Laboratorium 3.';
      break;
    case 'lab4':
      $page = './html/labor_175036_isi2.php';
      $title = 'Największe mosty świata - PHP';
      $headerTitle = 'PHP';
      $headerDescription = 'Laboratorium 4.';
      break;

    case 'filmy':
      $page = './html/filmy.html';
      $title = 'Największe mosty świata - Filmy o mostach';
      $headerTitle = 'Filmy o mostach';
      $headerDescription = 'Odkryj fascynujące filmy dokumentalne i edukacyjne o największych mostach świata, ich konstrukcji i historii.';
      break;
    default:
      $page = './html/404.html';
      $title = '404 Page does not exists.';
      break;
  }
  if (!file_exists($page)) {
    $page = './html/404.html';
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
  <?php if (isset($_GET['page']) && $_GET['page'] == 'jquery')
    echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'></script>"; ?>
  <title><?php echo $title; ?></title>
</head>

<body<?php if (isset($_GET['page']) && $_GET['page'] == 'scripts') echo " onload='startClock()'"; ?>>
  <header>
    <img src="./images/logo.png" alt="Logo Mosty" class="logo" />
    <div class="logo-content">
      <h1><?php echo $headerTitle; ?></h1>
      <p><?php echo $headerDescription; ?></p>
    </div>
  </header>
  <nav>
    <ul>
      <li><a href="./?page=main">Strona główna</a></li>
      <li><a href="./?page=about">O stronie</a></li>
      <li><a href="./?page=bridge_types">Typy mostów</a></li>
      <li><a href="./?page=gallery">Galeria</a></li>
      <li><a href="./?page=contact">Kontakt</a></li>
      <li><a href="./?page=scripts">Skrypty JS</a></li>
      <li><a href="./?page=jquery">jQuery</a></li>
      <li><a href="./?page=lab4">Lab 4 - PHP</a></li>
      <li><a href="./?page=filmy">Filmy</a></li>
    </ul>
  </nav>
  </header>
  <main>
    <?php include($page); ?>
  </main>

  <?php
  $nr_indeksu = '175036';
  $nrGrupy = 'ISI2';
  ?>
  <footer>
    <p>&copy; 2025 Największe mosty świata - <?php echo "Krzysztof Krupicki $nr_indeksu, $nrGrupy" ?></p>
  </footer>
  <?php if ($_GET['page'] == 'scripts')
    echo "<script src='./js/kolorujtlo.js'></script><script src='./js/timedate.js'></script>" ?>
  </body>

  </html>