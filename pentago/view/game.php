<?php
$need_awesome_font = true;
require_once 'help_view/header.php';
require_once 'help_view/default_modal.php';
?>
<div class="time-info" style="display: none">
    <?=$_GET['time']?>
</div>
<?php if ($current_user !== false){?>
<div class="main">
    <div class="game">
        <div class="game_preloader" style="display: block">
            <svg class="preloader__image" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z">
                </path>
            </svg>
            <br><br>
            Please wait we find your opponent
        </div>
        <div class="game_inner" style="display: none">
            <div class="game_player">
                <div class="player_burger">&#9776;</div>
                <a class="player_name">Serje</a>
                <div class="player_rating">980</div>
                <div class="player_time">30</div>
            </div>
            <div class="game_card">
                <div class="game_desk">
                    <div class="game_quarter-cont">
                        <div class="game_quarter" id="q0">
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                    <!--                                <div class="figure figure__white"></div>-->
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="game_quarter-cont">
                        <div class="game_quarter" id="q1">
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="game_quarter-cont">
                        <div class="game_quarter" id="q2">
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="game_quarter-cont">
                        <div class="game_quarter" id="q3">
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                            <div class="game_cell">
                                <div class="game_cell_inner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="game_panel">
                <div class="left-rotate rotate-btn"><i class="fas fa-undo"></i></div>
                <button class="give-up">Give Up</button>
                <div class="right-rotate rotate-btn"><i class="fas fa-redo"></i></div>
            </div>
            <div class="game_player game_player_2">
                <div class="player_burger">&#9776;</div>
                <div class="player_name"><?=$current_user -> get_name()?></div>
                <div class="player_rating"><?=$current_user -> get_rating()?></div>
                <div class="player_time">30</div>
            </div>
        </div>

    </div>
</div>


<script src="js/game_functions.js"></script>
<script src="js/game_script.js"></script>

<script>
    let game_card = document.querySelector('.game_card');
    let timeout;
    function set_game_card_width() {
        console.log(1);
        if (game_card.clientHeight > 0) {
            game_card.style.width = game_card.clientHeight + 'px';
            clearTimeout(timeout);
        }
        else {
            timeout = setTimeout(function () {
                set_game_card_width();
            }, 500);
        }
    }
    if (window.innerWidth > 800){
        set_game_card_width();
    }


</script>
<?php
    }
    else {
        echo '<div class="main"><div class="game">Login please. You can do it by button in menu. It is on the left side if you use computer. If you use smartphone you can open menu by button on the left top corner of your screen.</div></div>';
    }

    echo '<script>selected_id = -1</script>';
    require_once 'help_view/default_scripts.php';
?>
