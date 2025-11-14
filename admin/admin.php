<?php
session_start();
require '../cfg.php';

// --- OBSŁUGA AKCJI
if (isset($_POST['add_subpage_submit'])) {
    $new_title = $_POST['page_title'] ?? '';
    $new_content = $_POST['page_content'] ?? '';
    $new_status = isset($_POST['status']) ? 1 : 0;
    if (!empty($new_title) && !empty($new_content)) {
        global $link;
        $insert_query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$new_title', '$new_content', $new_status)";
        mysqli_query($link, $insert_query) or die(mysqli_error($link));
        header("Location: {$_SERVER['PHP_SELF']}?success=1");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=1");
        exit();
    }
}

if (isset($_POST['edit_subpage_submit']) && isset($_GET['edit_id'])) {
    $edit_id = (int) $_GET['edit_id'];
    $new_title = $_POST['page_title'] ?? '';
    $new_content = $_POST['page_content'] ?? '';
    $new_status = isset($_POST['status']) ? 1 : 0;
    if (!empty($new_title) && !empty($new_content)) {
        global $link;
        $update_query = "UPDATE page_list SET page_title='$new_title', page_content='$new_content', status=$new_status WHERE id=$edit_id LIMIT 1";
        mysqli_query($link, $update_query) or die(mysqli_error($link));
        header("Location: {$_SERVER['PHP_SELF']}?edit_success=1");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?edit_id=$edit_id&error=1");
        exit();
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    if (!empty($delete_id) && is_numeric($delete_id)) {
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

// --- GENEROWANIE HTML
echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>';

if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true) {
    $_SESSION['zalogowany'] = true;
    echo "<h1 class='admin_panel_heading'>Panel administracyjny</h1>";
    RenderPanel();
} else {
    $_SESSION['zalogowany'] = false;
    echo FormularzLogowania();

    if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
        $form_login = $_POST['login_email'];
        $form_pass = $_POST['login_pass'];
        if ($form_login == $login && $form_pass == $pass) {
            $_SESSION['zalogowany'] = true;
            echo "Zalogowano pomyślnie!<br/><br/>";
            echo "<h1 class='admin_panel_heading'>Panel administracyjny</h1>";
            RenderPanel();
        } else {
            echo "<b style='color: red;'>Niepoprawny login lub hasło!</b>";
            echo FormularzLogowania();
            exit();
        }
    } else {
        echo FormularzLogowania();
        exit();
    }
}

// --- FUNKCJE PANELU
function FormularzLogowania()
{
    $wynik = "
    <div class='logowanie'>
        <h1 class='heading'>Panel CMS</h1>
        <div class='logowanie'>
            <form action='{$_SERVER["REQUEST_URI"]}' method='post' name='LoginForm' enctype='multipart/form-data'>
                <table class='logowanie'>
                    <tr>
                        <td class='log4_t'>[email]</td>
                        <td><input type='text' name='login_email' class='logowanie'></td>
                    </tr>
                    <tr>
                        <td class='log4_t'>[haslo]</td>
                        <td><input type='password' name='login_pass'class='logowanie'></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' name='x1_submit' value='Zaloguj' class='logowanie'></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    ";
    return $wynik;
}

function ListaPodstron()
{
    global $link;
    $query = "SELECT * FROM page_list";
    if (!$link)
        die("<b>Przerwano połączenie z bazą danych!</b>");

    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    echo "<div class='subpage_list'>";
    echo "<h3 class='subpage_list_heading'>Lista podstron</h3>";
    while ($row = mysqli_fetch_array($result)) {
        $status = $row['status'] ? 'AKTYWNA' : 'NIEAKTYWNA';
        echo "<div class='subpage_item'>
            <p>ID: <b>{$row['id']}</b> &nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp Tytuł podstrony: <b>{$row['page_title']}</b></p>
            <p>Status podstrony: <b>{$status}</b></p>
            <div class='subpage_actions'>
                <div class='actions_button'>
                    <a href='?edit_id={$row['id']}' class='btn_edit'>EDYTUJ</a>
                </div>
                <div class='actions_button'>
                    <a href='?delete_id={$row['id']}' class='btn_del'>USUŃ</a>
                </div>
            </div>
        </div>";
    }
    echo "</div>";
}

function EdytujPodstrone($id)
{
    global $link;
    $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $page = mysqli_fetch_array($result);
    if (!$page)
        die("<b>Brak podstrony o podanym ID!</b>");

    $form_error = '';
    $success_msg = '';

    if (isset($_GET['error']) && $_GET['error'] == 1) {
        $form_error = "<p class='warning'>Wszystkie pola muszą być wypełnione!</p>";
    }
    if (isset($_GET['edit_success']) && $_GET['edit_success'] == 1) {
        $success_msg = "<p class='success'>Podstrona została zaktualizowana pomyślnie!</p>";
    }

    $status_checked = $page['status'] ? 'checked' : '';
    echo "
        <div class='edit_subpage'>
            <h3 class='subpage_form_heading'>Formularz edycji podstrony</h3>
            {$success_msg}
            {$form_error}
            <form class='EditSubpageForm' method='POST' action='{$_SERVER['PHP_SELF']}?edit_id={$id}' enctype='multipart/form-data'>
                <label for='page_title'>Tytuł podstrony:</label><br/>
                <input type='text' name='page_title' value='{$page['page_title']}' required/>
                <br/>
                <label for='page_content'>Treść podstrony:</label><br/>
                <textarea name='page_content' rows='10' cols='50' required>{$page['page_content']}</textarea>
                <br/>
                <label for='status'>Status podstrony:</label>
                <input type='checkbox' id='status' name='status' {$status_checked}/>
                <br/>
                <button name='edit_subpage_submit'>Zapisz zmiany</button>
            </form>
        </div>
    ";
}

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
            <h3 class='subpage_form_heading'>Formularz tworzenia nowej podstrony</h3>
            {$success_msg}
            {$form_error}
            <form class='NewSubpageForm' method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>
                <label for='page_title'>Tytuł podstrony:</label><br/>
                <input type='text' id='page_title' name='page_title' value='{$new_title}' required/>
                <br/>
                <label for='page_content'>Treść podstrony:</label><br/>
                <textarea id='page_content' name='page_content' rows='10' cols='50' required>{$new_content}</textarea>
                <br/>
                <label for='status'>Status podstrony:</label>
                <input type='checkbox' id='status' name='status' " . ($new_status ? 'checked' : '') . ">
                <br/>
                <button name='add_subpage_submit'>Dodaj nową podstronę</button>
            </form>
        </div>
    ";
}

function RenderPanel()
{
    if (isset($_GET['edit_id'])) {
        $edit_id = (int) $_GET['edit_id'];
        EdytujPodstrone($edit_id);
    }
    DodajNowaPodstrone();
    ListaPodstron();
}
?>