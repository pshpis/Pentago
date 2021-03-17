<?php require_once 'help_view/header.php';?>
<div class="main">
    <?php require_once 'help_view/default_modal.php';?>
    <h2>Play Online!</h2><br>
    <div class="play">
        <a class="play_cell" href="play?time=60">1<br> min</a>
        <a class="play_cell" href="play?time=180">3<br> min</a>
        <a class="play_cell" href="play?time=300">5<br> min</a>
        <a class="play_cell" href="play?time=600">10<br> min</a>
        <a class="play_cell" href="play?time=900">15<br> min</a>
        <a class="play_cell" href="play?time=1800">30<br> min</a>
    </div>
    <h2>Games Now</h2>
    <div class="games">
        <div class="games_cell">
            <div class="player player1">
                <div class="player_name">Serje</div>
                <div class="player_rating">892</div>
            </div>
            <div class="player player2">
                <div class="player_rating">823</div>
                <div class="player_name">Peter</div>
            </div>
        </div>
        <div class="games_cell">
            <div class="player player1">
                <div class="player_name">Serje</div>
                <div class="player_rating">892</div>
            </div>
            <div class="player player2">
                <div class="player_rating">823</div>
                <div class="player_name">Peter</div>
            </div>
        </div>
        <div class="games_cell">
            <div class="player player1">
                <div class="player_name">Serje</div>
                <div class="player_rating">892</div>
            </div>
            <div class="player player2">
                <div class="player_rating">823</div>
                <div class="player_name">Peter</div>
            </div>
        </div>
        <div class="games_cell">
            <div class="player player1">
                <div class="player_name">Serje</div>
                <div class="player_rating">892</div>
            </div>
            <div class="player player2">
                <div class="player_rating">823</div>
                <div class="player_name">Peter</div>
            </div>
        </div>
    </div>
</div>

<script>
    let selected_id = 0;
</script>

<?php require_once 'help_view/default_scripts.php';
echo __DIR__;
?>
</body>
</html>
