// gaming vars start

let my_id;
let side;
let cond = [];
let rotate = "0000";
let game_start = false;
let step_complete = 0, rotate_complete = 0;
let current_quarter = -1;
let my_time = 0, opponent_time = 0;
let my_clock, opponent_clock;
let opponent_name, opponent_rating;
let start_time = +document.querySelector('.time-info').innerHTML;
let opponent_id;

//gaming vars end

//html constants start (with CamilCase)

const preloader = document.querySelector('.game_preloader');
const gameInner = document.querySelector('.game_inner');
const desk = document.querySelector('.game_desk');
const quarters = document.querySelectorAll('.game_quarter');
const players = document.querySelectorAll('.game_player');
const playerNames = document.querySelectorAll('.player_name');
const playersTime = document.querySelectorAll('.player_time');
const playersRating = document.querySelectorAll('.player_rating');
const leftRotate = document.querySelector('.left-rotate');
const rightRotate = document.querySelector('.right-rotate');
const giveUpBtn = document.querySelector('.give-up');

//html constants end


const ws = new WebSocket('ws://a0446139.xsph.ru:8090/controller/websocket_controller.php');

ws.onopen = async function (event) {
    await set_id();
    let send_data = {
        user_id: my_id,
        make_game: true,
        start_time: start_time,
    };

    ws.send(JSON.stringify(send_data));
}

ws.onmessage = function (event) {
    let data = JSON.parse(event.data);
    console.log(data);
    if (data.hasOwnProperty('side')) side = data.side;

    if (data.hasOwnProperty('players_count') && data.players_count === 2 && !game_start){
        game_start = true;
        remove_preloader();
        watch_desk();

        cond = str_to_arr(data.cond);
        rotate = data.rotate;
        step_complete = data.step_complete;
        rotate_complete = data.rotate_complete;
        my_time = data.my_time;
        opponent_id = data.opponent_id;
        opponent_time = data.opponent_time;
        opponent_name = data.opponent_name;
        opponent_rating = data.opponent_rating;

        set_opponent_name(opponent_name);
        set_opponent_rating(opponent_rating);
        set_opponent_href(opponent_id);

        set_players_colors();

        let moves_complete = Math.min(+step_complete, +rotate_complete);

        console.log(my_time);
        console.log(opponent_time);

        set_my_time(my_time);
        set_opponent_time(opponent_time);

        if (side === 1 && moves_complete % 2 === 0) start_my_clock();
        else if (side === 2 && moves_complete % 2 === 1) start_my_clock();
        else start_opponent_clock();

        update_desk();

        desk.addEventListener('click', function (event) {
            let target = event.target;
            let cell = target.closest('.game_cell');
            if (!cell) return;

            if (step_complete !== rotate_complete) return;
            if (step_complete % 2 + 1 !== side) return;

            if (!make_step(+cell.id.slice(1), side)) return;

            let send_data = {
                user_id: my_id,
                step: +cell.id.slice(1),
                rotate_quarter: -1,
                rotate_dir: -2,
            }

            step_complete ++;

            ws.send(JSON.stringify(send_data));
        });

        desk.addEventListener('click', function (event) {
            let target = event.target;
            let quarter = target.closest('.game_quarter');
            if (!quarter) return;

            current_quarter = +quarter.id.slice(1);
            select_quarter(quarter);
        });

        rightRotate.addEventListener('click', function (event) {
            if (current_quarter === -1) return;
            if (rotate_complete !== step_complete - 1) return;
            if (rotate_complete % 2 + 1 !== side) return;

            rotate_quarter(current_quarter, 1);

            let send_data = {
                user_id: my_id,
                step: -1,
                rotate_quarter: current_quarter,
                rotate_dir: 1
            }
            ws.send(JSON.stringify(send_data));

            rotate_complete ++;
        });

        leftRotate.addEventListener('click', function (event) {
            if (current_quarter === -1) return;
            if (rotate_complete !== step_complete - 1) return;
            if (rotate_complete % 2 + 1 !== side) return;
            rotate_quarter(current_quarter, -1);
            let send_data = {
                user_id: my_id,
                step: -1,
                rotate_quarter: current_quarter,
                rotate_dir: -1
            }
            ws.send(JSON.stringify(send_data));

            rotate_complete ++;
        });

        giveUpBtn.addEventListener('click', function (event) {
            let send_data = {
                user_id: my_id,
                give_up: true
            }
            ws.send(JSON.stringify(send_data));
        });
    }

    if (data.hasOwnProperty('step') && data.step !== -1){
        // document.querySelector('#c' + data.step).append(get_figure(3-side));
        make_step(data.step, 3 - side);
        step_complete ++;
    }

    if (data.hasOwnProperty('rotate_quarter') && data.rotate_quarter !== -1){
        let quarter = quarters[+data.rotate_quarter];
        let delta_rotate = data.rotate_dir;
        quarter.style.transform = "rotate(" + (parseInt(quarter.style.transform.slice(7)) + (delta_rotate * 90)) + "deg)";
        rotate_complete ++;

        opponent_time = data.opponent_time;
        set_opponent_time(opponent_time);
        start_my_clock();
        clearInterval(opponent_clock);
    }

    if (data.hasOwnProperty('winner') && data.winner !== 0){
        let center_el = document.querySelector('.game_inner');
        data.winner = + data.winner;
        if (data.winner === 3) center_el.innerHTML = 'It was a draw';
        if (data.winner === side) center_el.innerHTML = 'You win!';
        else center_el.innerHTML = 'You lose :(';
    }

    if (data.hasOwnProperty('my_time') && !data.hasOwnProperty('players_count')){
        my_time = data.my_time;
        set_my_time(my_time);
        clearInterval(my_clock);
        start_opponent_clock();
    }
}

let end_search_data = JSON.stringify({
    user_id: my_id,
    end_search: true,
});

window.onbeforeunload = function() {
    // return false;
    let is_preloader = (preloader.clientHeight > 0);
    if (is_preloader){
        ws.send(end_search_data);
    }
};