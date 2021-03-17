<?php require_once 'help_view/header.php';?>

<div class="main">
    <?php require_once 'help_view/default_modal.php';?>
    <div class="friends">
        <div class="friends_card">
            <h2>List of friends</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Rating</th>
                </tr>
                <tr>
                    <td>Monster</td>
                    <td>870</td>
                </tr>
                <tr>
                    <td>Andrew</td>
                    <td>890</td>
                </tr>
                <tr>
                    <td>You</td>
                    <td>860</td>
                </tr>
                <tr>
                    <td>Serje</td>
                    <td>900</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    let selected_id = 3;
</script>

<?php require_once 'help_view/default_scripts.php';?>
</body>
</html>