function remove_preloader() {
    preloader.style.display = 'none';
}

function watch_desk() {
    gameInner.style.display = 'block';
    set_cell_id();
    set_default_quarter_rotate();
}

function set_cell_id() {
    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 6; j++) {
            let quarter = 0;
            if (i >= 3 && j >= 3) quarter = 3;
            if (i < 3 && j >= 3) quarter = 1;
            if (i >= 3 && j < 3) quarter = 2;
            quarters[quarter].children[(i % 3)  * 3 + j % 3].id = 'c' + (i * 6 + j);
            cond.push(0);
        }
    }
}

function set_default_quarter_rotate() {
    quarters.forEach(q => {
        q.style.transform = 'rotate(0deg)';
    });
}

function set_my_time(need_time) {
    playersTime[1].innerHTML = getTimeStr(need_time);
    my_time = need_time;
}

function set_opponent_time(need_time) {
    playersTime[0].innerHTML = getTimeStr(need_time);
    opponent_time = need_time;
}

function set_players_colors() {
    playersRating[0].after(get_figure(3 - side));
    playersRating[1].after(get_figure(side));
}

function set_opponent_href(opponent_id) {
    playerNames[0].setAttribute('href', 'account?id=' + opponent_id);
}

function getTimeStr(currentTime){
    let secondsPassed = '' + Math.floor(currentTime) % 60;
    let minutesPassed = '' + Math.floor(currentTime/60) % 60;
    let hoursPassed = '' + Math.floor(minutesPassed/60);

    let timeStr = '';
    if (hoursPassed > 0){
        if (minutesPassed.length === 1) minutesPassed = '0' + minutesPassed;
        timeStr = hoursPassed + ':' + minutesPassed + ':' + secondsPassed;
    }
    else if (minutesPassed > 0) {
        if (secondsPassed.length === 1) secondsPassed = '0' + secondsPassed;
        timeStr = minutesPassed + ':' + secondsPassed;
    }
    else timeStr = secondsPassed;

    return timeStr;
}

function start_my_clock(){
    my_clock = setInterval(function () {
        my_time --;
        if (my_time < 0) {
            let send_data = {
                user_id: my_id,
                give_up: true
            }
            ws.send(JSON.stringify(send_data));
        }
        set_my_time(my_time);
    }, 1000);
}

function start_opponent_clock(){
    opponent_clock = setInterval(function () {
        opponent_time --;
        if (opponent_time > 0) set_opponent_time(opponent_time);
        else {
            let temp = opponent_time;
            set_opponent_time(0);
            opponent_time = temp;
        }
        if (opponent_time < -2){
            let send_data = {
                user_id: my_id,
                opponent_leave: true,
            }
            ws.send(JSON.stringify(send_data));
        }
    }, 1000);
}

async function set_id() {
    let response = await fetch('controller/ajax_game_controller.php?get_id');
    let text = await response.text();
    console.log("My id is ", text);
    my_id = + text;
}

function rotate_quarter(quarter_num, dir) {
    let quarter = quarters[quarter_num];
    quarter.style.zIndex = '10000';
    let delta_rotate = dir;

    console.log(quarter);
    quarter.style.transform = "rotate(" + (parseInt(quarter.style.transform.slice(7)) + (delta_rotate * 90)) + "deg)";
    console.log("rotate(" + (parseInt(quarter.style.transform.slice(7)) + (delta_rotate * 90)) + "deg)");
    console.log(parseInt(quarter.style.transform.slice(7)));
    console.log((parseInt(quarter.style.transform.slice(7)) + (delta_rotate * 90)));
    console.log(quarter);

    quarter.style.zIndex = '1';
    unselect_all_quarters();
}

function unselect_all_quarters() {
    quarters.forEach(q => {
        let quarter_cont = q.closest('.game_quarter-cont');
        quarter_cont.classList.remove('quarter__choose');
    });
}

function select_quarter(quarter) {
    unselect_all_quarters();
    let quarter_cont = quarter.closest('.game_quarter-cont');
    quarter_cont.classList.add('quarter__choose');
}

function make_step(cell_id, step_side) {
    if (step_side <= 0 || step_side > 2) return false;
    let cell = document.querySelector('#c' + cell_id);
    let cell_inner = cell.firstElementChild;
    if (cell_inner.firstElementChild) return false;
    console.log(cell_inner);
    cell_inner.append(get_figure(step_side));

    cond[cell_id] = step_side;
    return true;
}

function get_figure(side) {
    let figure = document.createElement('div');
    figure.classList.add('figure');

    if (side === 1) {
        figure.classList.add('figure__white');
    } else {
        figure.classList.add('figure__black');
    }

    return figure;
}

function str_to_arr(str) {
    let arr = [];
    for (let i = 0; i < str.length; i++) arr.push(+str[i]);
    return arr;
}

function get_quarter_rotate(quarter){
    let rotate = quarter.style.transform;
    rotate = parseInt(rotate.slice(7));
    if (rotate < 0){
        let k = Math.floor(-rotate/360);
        rotate += (k + 1) * 360;
    }

    return (rotate / 90) % 4;
}

function update_desk() {
    for (let i = 0; i < cond.length; i++) {
        let cell = document.querySelector('#c' + i);
        cell = cell.firstElementChild;
        if (cond[i] === 0) cell.innerHTML = '';
        else {
            if (cell.children.length === 0) cell.append(get_figure(cond[i]));
            else {
                let cell_figure_color = -1;
                if (cell.firstElementChild.classList.contains('figure__white')) cell_figure_color = 1;
                if (cell.firstElementChild.classList.contains('figure__black')) cell_figure_color = 2;

                if (cell_figure_color !== cond[i]) {
                    cell.innerHTML = '';
                    cell.append(get_figure(cond[i]));
                }
            }
        }
    }

    for (let i = 0; i < 4; i ++){
        let quarter = quarters[i];
        let default_rotate = get_quarter_rotate(quarter);
        if ('' + default_rotate === rotate[i]) continue;
        // console.log(rotate);
        let need_rotate = +rotate[i];
        let delta_rotate = 0;
        if (need_rotate > default_rotate && !(need_rotate === 3 && default_rotate === 0) || (need_rotate === 0 && default_rotate === 3)) delta_rotate = 1;
        else delta_rotate = -1;

        console.log("default_rotate is " + default_rotate);
        console.log("need_rotate is " + need_rotate);
        console.log("delta_rotate is " + delta_rotate);
        console.log(parseInt(quarter.style.transform.slice(7)));

        quarter.style.transform = "rotate(" + (parseInt(quarter.style.transform.slice(7)) + (delta_rotate * 90)) + "deg)";
    }
}

function set_opponent_name(opponent_name) {
    players[0].querySelector('.player_name').innerHTML = opponent_name;
}

function set_opponent_rating(opponent_rating) {
    players[0].querySelector('.player_rating').innerHTML = opponent_rating;
}

// ----------- all functions up are ok

function create_desk() {
    let desk = document.createElement('div');
    desk.classList.add('desk');

    for (let i = 0; i < 4; i++) {
        let quarter_cont = document.createElement('div');
        quarter_cont.classList.add('quarter-cont');
        quarter_cont.id = "w" + i;


        let quarter = document.createElement('div');
        quarter.classList.add('quarter');
        quarter.id = "q" + i;

        quarter_cont.append(quarter);
        desk.append(quarter_cont);
    }

    document.querySelector('.center-el').prepend(desk);
    let quarter_list = Array.from(document.querySelectorAll('.quarter'));

    for (let i = 0; i < quarter_list.length; i ++) quarter_list[i].style.transform = "rotate(0deg)";

    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 6; j++) {
            let cell = document.createElement('div');
            cell.classList.add('cell');
            cell.id = 'c' + (i * 6 + j);
            let quarter = 0;
            if (i >= 3 && j >= 3) quarter = 3;
            if (i < 3 && j >= 3) quarter = 1;
            if (i >= 3 && j < 3) quarter = 2;
            quarter_list[quarter].append(cell);
            cond.push(0);
        }
    }

    let space = document.createElement('div');
    space.classList.add('space-between-container');
    space.classList.add('bottom-form')

    let left_rotate = document.createElement('div');
    left_rotate.classList.add('left_rotate');
    let img1 = document.createElement('img');
    img1.setAttribute('src', "img/left_arrow.png");
    left_rotate.append(img1);

    let button = document.createElement('button');
    button.classList.add('give-up-btn');
    button.classList.add('btn-primary');
    button.innerText = 'give up';

    let right_rotate = document.createElement('div');
    right_rotate.classList.add('right_rotate');
    let img2 = document.createElement('img');
    img2.setAttribute('src', "img/right_arrow.png");
    right_rotate.append(img2);

    space.append(left_rotate);
    space.append(button);
    space.append(right_rotate);
    document.querySelector('.desk').after(space);
}