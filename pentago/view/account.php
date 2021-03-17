<?php require_once 'help_view/header.php';?>
<div class="main">
    <?php
    require_once 'help_view/default_modal.php';
    require_once '../component/watch_account_component.php';
    require_once '../component/go_out_component.php';

    if ($current_user === false && $watch_user === false) header('Location: main');
    ?>
    <div class="account">
        <div class="account_card">
            <h2 class="account_name"><?=$watch_user -> get_name()?></h2>
            <table>
                <tr>
                    <td>Rating:</td>
                    <td><?=$watch_user -> get_rating()?></td>
                </tr>
                <tr>
                    <td>Games count:</td>
                    <td><?=$watch_user -> get_games_count()?></td>
                </tr>
                <tr>
                    <td>Won games:</td>
                    <td><?=$watch_user -> get_won_games()?></td>
                </tr>
                <tr>
                    <td>Lose games:</td>
                    <td><?=$watch_user -> get_lose_games()?></td>
                </tr>
                <tr>
                    <td>Draw games:</td>
                    <td><?=$watch_user -> get_draw_games()?></td>
                </tr>
            </table>
            <form action="" method="post">
                <button name="go_out" type="submit" class="btn-primary" style="margin-top: 10px;">Go out</button>
            </form>
        </div>
    </div>



</div>

<script>
    let selected_id = 1;
</script>

<?php require_once 'help_view/default_scripts.php'?>
</body>
</html>