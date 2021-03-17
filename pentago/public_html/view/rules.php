<?php require_once 'help_view/header.php';?>

<div class="main">
    <?php require_once 'help_view/default_modal.php';?>
    <div class="rules">
        <div class="rules_card">
            <h2>Penatgo rules</h2>
            <br>
            Pentago is a two-player abstract strategy game invented by Tomas Flodén.
            The game is played on a 6×6 board divided into four 3×3 sub-boards (or quadrants). Taking turns, the two players place a marble of their color (either black or white) onto an unoccupied space on the board, and then rotate one of the sub-boards by 90 degrees either clockwise or anti-clockwise. This is optional in the beginning of the game, up until every sub-board no longer has rotational symmetry, at which point it becomes mandatory (this is because until then, a player could rotate an empty sub-board or one with just a marble in the middle, either of which has no real effect). A player wins by getting five of their marbles in a vertical, horizontal or diagonal row (either before or after the sub-board rotation in their move). If all 36 spaces on the board are occupied without a row of five being formed then the game is a draw.
            <br><br><br>
            *This information was taken from <a href="https://en.wikipedia.org/wiki/Pentago">Wikipedia</a>
        </div>
    </div>
</div>

<script>
    let selected_id = 4;
</script>

<?php require_once 'help_view/default_scripts.php';?>
</body>
</html>