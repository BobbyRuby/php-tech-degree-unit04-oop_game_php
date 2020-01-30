<?php
session_start();
// Autoloader to load classes on demand
include "./inc/autoload.php";
// If no phrase has been set
if (empty($_SESSION['phrase'])) {
    // Create new Phrase object - it selects a random phrase for us on instantiation
    $phrase = new Phrase();
    $_SESSION['phrase'] = $phrase->getCurrentPhrase();
} // Else a phrase has been set
else {
    // POSTed
    if ( ! empty($_POST) ):
        // Not Reset!
        if ( empty($_POST['reset']) ) {
            $_SESSION['selected'][] = $_POST['key'];
            $_SESSION['selected'] = array_unique($_SESSION['selected']);
            $selected = $_SESSION['selected'];
            // Use already selected phrase and pass selected characters to instantiate Phrase object
            $phrase = new Phrase($_SESSION['phrase'], $selected);
        } // Reset!
        else {
            // Destroy session and reload page
            session_destroy();
            header("Location: play.php");
        }
    // Page reloaded without Posting - use existing phrase and selected characters
    else:
        $selected = ! empty($_SESSION['selected']) ? $_SESSION['selected'] : [];
        // Use already selected phrase and pass selected characters to instantiate Phrase object
        $phrase = new Phrase($_SESSION['phrase'], $selected);
    endif;
}

// Instantiate a Game object
//$game = new Game($phrase, $incorrect, $correct);
$game = new Game($phrase);
$game->determineCorrectIncorrectKeyPresses();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Phrase Hunter</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script src="js/play.js"></script>
        <link href="css/styles.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    </head>
    <body>
        <div class="main-container">
            <h2 class="header">Phrase Hunter</h2>
            <?php
            if ( $game->checkForWin() || $game->checkForLose() ):
                $game->gameOver();
                session_destroy();
                echo '<form id="keyboard" action="play.php" method="post">'.
                    $game->displayResetButton().
                    '</form>';
            else:
                echo $phrase->addPhraseToDisplay();
                echo '<form id="keyboard" action="play.php" method="post">'.
                    $game->displayKeyboard().
                    '</form>';
                echo $game->displayScore();
            endif;
            ?>
        </div>
    </body>
</html>