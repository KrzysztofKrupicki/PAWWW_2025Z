<?php
/**
 * Panel administracyjny
 * Umożliwia zarządzanie podstronami (dodawanie, edycja, usuwanie).
 */

session_start();
require '../cfg.php';

// --- OBSŁUGA AKCJI ---

// Dodawanie nowej podstrony
if (isset($_POST['add_subpage_submit'])) {
    $new_title = $_POST['page_title'] ?? '';
    $new_content = $_POST['page_content'] ?? '';
    $new_status = isset($_POST['status']) ? 1 : 0;

    if (!empty($new_title) && !empty($new_content)) {
        global $link;
        // Zabezpieczenie przed SQL Injection
        $new_title = mysqli_real_escape_string($link, $new_title);
        $new_content = mysqli_real_escape_string($link, $new_content);

        $insert_query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$new_title', '$new_content', $new_status)";
        mysqli_query($link, $insert_query) or die(mysqli_error($link));

        header("Location: {$_SERVER['PHP_SELF']}?success=1");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=1");
        exit();
    }
}

// Edycja podstrony
if (isset($_POST['edit_subpage_submit']) && isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $new_title = $_POST['page_title'] ?? '';
    $new_content = $_POST['page_content'] ?? '';
    $new_status = isset($_POST['status']) ? 1 : 0;

    if (!empty($new_title) && !empty($new_content)) {
        global $link;
        // Zabezpieczenie przed SQL Injection
        $new_title = mysqli_real_escape_string($link, $new_title);
        $new_content = mysqli_real_escape_string($link, $new_content);

        $update_query = "UPDATE page_list SET page_title='$new_title', page_content='$new_content', status=$new_status WHERE id=$edit_id LIMIT 1";
        mysqli_query($link, $update_query) or die(mysqli_error($link));

        header("Location: {$_SERVER['PHP_SELF']}?edit_success=1");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?edit_id=$edit_id&error=1");
        exit();
    }
}

// Usuwanie podstrony
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    if ($delete_id > 0) {
        global $link;
        $delete_query = "DELETE FROM page_list WHERE id = $delete_id LIMIT 1";
        mysqli_query($link, $delete_query) or die(mysqli_error($link));

        header("Location: {$_SERVER['PHP_SELF']}?delete_success=1");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?delete_error=1");
        exit();
    }
}

// --- GENEROWANIE HTML ---
echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>';

// Sprawdzenie sesji logowania
if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true) {
    echo "<h1 class='admin_panel_heading'>Panel administracyjny</h1>";
    RenderPanel();
    exit();
}

// Logowanie
if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
    $form_login = $_POST['login_email'];
    $form_pass = $_POST['login_pass'];

    if ($form_login === $login && $form_pass === $pass) {
        $_SESSION['zalogowany'] = true;

        // Przeładowanie strony po zalogowaniu, aby uniknąć ponownego przesyłania formularza
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "<div class='container'>";
        echo FormularzLogowania();
        echo "<p class='error'>Niepoprawny login lub hasło!</p>";
        echo "</div>";
        echo "</body></html>";
        exit();
    }
} else {
    // Wyświetlenie formularza logowania jeśli nie przesłano danych i nie jest zalogowany
    echo "<div class='container'>";
    echo FormularzLogowania();
    echo "</div>";
    echo "</body></html>";
    exit();
}

// --- FUNKCJE PANELU ---

/**
 * Generuje formularz logowania
 */
function FormularzLogowania()
{
    return "
    <div class='logowanie'>
        <h1 class='heading'>Panel CMS</h1>
        <div class='logowanie-form'>
            <form action='{$_SERVER["REQUEST_URI"]}' method='post' name='LoginForm' enctype='multipart/form-data'>
                <table class='logowanie-table'>
                    <tr>
                        <td class='log4_t'>Login:</td>
                        <td><input type='text' name='login_email' class='logowanie-input'></td>
                    </tr>
                    <tr>
                        <td class='log4_t'>Hasło:</td>
                        <td><input type='password' name='login_pass' class='logowanie-input'></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' name='x1_submit' value='Zaloguj' class='logowanie-btn'></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    ";
}

/**
 * Wyświetla listę podstron
 */
function ListaPodstron()
{
    global $link;
    $query = "SELECT * FROM page_list ORDER BY id ASC";
    if (!$link) {
        die("<b>Przerwano połączenie z bazą danych!</b>");
    }

    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    echo "<div class='subpage_list'>";
    echo "<h3 class='subpage_list_heading'>Lista podstron</h3>";

    while ($row = mysqli_fetch_array($result)) {
        $status = $row['status'] ? '<span class="status-active">AKTYWNA</span>' : '<span class="status-inactive">NIEAKTYWNA</span>';
        // htmlspecialchars dla wyświetlania danych z bazy
        $title = htmlspecialchars($row['page_title']);

        echo "<div class='subpage_item'>
            <div class='subpage_info'>
                <p>ID: <b>{$row['id']}</b> | Tytuł: <b>{$title}</b></p>
                <p>Status: <b>{$status}</b></p>
            </div>
            <div class='subpage_actions'>
                <a href='?edit_id={$row['id']}' class='btn_edit'>EDYTUJ</a>
                <a href='?delete_id={$row['id']}' class='btn_del' onclick='return confirm(\"Czy na pewno chcesz usunąć tę podstronę?\")'>USUŃ</a>
            </div>
        </div>";
    }
    echo "</div>";
}

/**
 * Wyświetla formularz edycji podstrony
 */
function EdytujPodstrone($id)
{
    global $link;
    $id = intval($id);
    $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $page = mysqli_fetch_array($result);

    if (!$page) {
        echo "<p class='error'>Brak podstrony o podanym ID!</p>";
        return;
    }

    $form_error = '';
    $success_msg = '';

    if (isset($_GET['error']) && $_GET['error'] == 1) {
        $form_error = "<p class='warning'>Wszystkie pola muszą być wypełnione!</p>";
    }
    if (isset($_GET['edit_success']) && $_GET['edit_success'] == 1) {
        $success_msg = "<p class='success'>Podstrona została zaktualizowana pomyślnie!</p>";
    }

    $escaped_content = htmlspecialchars($page['page_content']);
    $escaped_title = htmlspecialchars($page['page_title']);
    $status_checked = $page['status'] ? 'checked' : '';

    echo "
        <div class='edit_subpage'>
            <h3 class='subpage_form_heading'>Edycja podstrony: ID $id</h3>
            {$success_msg}
            {$form_error}
            <form class='EditSubpageForm' method='POST' action='{$_SERVER['PHP_SELF']}?edit_id={$id}' enctype='multipart/form-data'>
                <div class='form-group'>
                    <label for='page_title'>Tytuł podstrony:</label>
                    <input type='text' name='page_title' value='{$escaped_title}' required class='form-input'/>
                </div>
                
                <div class='form-group'>
                    <label for='page_content'>Treść podstrony:</label>
                    <textarea name='page_content' rows='10' cols='50' required class='form-textarea'>{$escaped_content}</textarea>
                </div>
                
                <div class='form-group checkbox-group'>
                    <label for='status'>Status (aktywna):</label>
                    <input type='checkbox' id='status' name='status' {$status_checked}/>
                </div>
                
                <div class='form-actions'>
                    <button name='edit_subpage_submit' class='btn_save'>Zapisz zmiany</button>
                    <a href='{$_SERVER['PHP_SELF']}' class='btn_cancel'>Anuluj</a>
                </div>
            </form>
        </div>
    ";
}

/**
 * Wyświetla formularz dodawania nowej podstrony
 */
function DodajNowaPodstrone()
{
    $new_title = '';
    $new_content = '';
    $new_status = 0;
    $form_error = '';
    $success_msg = '';

    if (isset($_GET['error']) && $_GET['error'] == 1) {
        $form_error = "<p class='warning'>Wszystkie pola muszą być wypełnione!</p>";
    }
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        $success_msg = "<p class='success'>Podstrona została dodana pomyślnie!</p>";
    }

    echo "
        <div class='new_subpage'>
            <h3 class='subpage_form_heading'>Dodaj nową podstronę</h3>
            {$success_msg}
            {$form_error}
            <form class='NewSubpageForm' method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>
                <div class='form-group'>
                    <label for='page_title_new'>Tytuł podstrony:</label>
                    <input type='text' id='page_title_new' name='page_title' value='{$new_title}' required class='form-input'/>
                </div>
                
                <div class='form-group'>
                    <label for='page_content_new'>Treść podstrony:</label>
                    <textarea id='page_content_new' name='page_content' rows='10' cols='50' required class='form-textarea'>{$new_content}</textarea>
                </div>
                
                <div class='form-group checkbox-group'>
                    <label for='status_new'>Status (aktywna):</label>
                    <input type='checkbox' id='status_new' name='status'>
                </div>
                
                <div class='form-actions'>
                    <button name='add_subpage_submit' class='btn_add'>Dodaj nową podstronę</button>
                </div>
            </form>
        </div>
    ";
}

/**
 * Główna funkcja renderująca panel
 */
function RenderPanel()
{
    if (isset($_GET['edit_id'])) {
        $edit_id = intval($_GET['edit_id']);
        EdytujPodstrone($edit_id);
        echo "<hr class='separator'>";
    }

    DodajNowaPodstrone();
    echo "<hr class='separator'>";
    ListaPodstron();
}
?>