<script>
    {
        let top_nav = document.querySelector('.top-nav')
        let side_nav = document.querySelector('.side-nav');
        let main = document.querySelector('.main');
        let burger = document.querySelector('.burger');
        let closebtn = document.querySelector('.closebtn');

        if (selected_id >= 0) side_nav.children[selected_id + 2].classList.add('__selected');

        burger.addEventListener('click', function (evt) {
            side_nav.style.width = '100%';
            top_nav.style.display = 'none';
            main.style.paddingTop = '0';
        });

        closebtn.addEventListener('click', function (evt) {
            side_nav.style.width = '0';
            top_nav.style.display = 'block';
            main.style.paddingTop = '52px';
        });
    }
</script>

<script>
    {
        let modal_close_array = document.querySelectorAll('.modal_close');
        modal_close_array.forEach(function (modal_close) {
            modal_close.addEventListener('click', function (evt) {
                let modal = modal_close.closest('.modal');
                modal.style.display = "none";
            });
        })

        let modal_btn_array = document.querySelectorAll('.modal-btn');
        modal_btn_array.forEach(function (modal_btn) {
            console.log(modal_btn);
            modal_btn.addEventListener('click', function (evt) {
                evt.preventDefault();
                let modal = document.querySelector('#modal-' + modal_btn.id.slice(-1));
                modal.style.display = "block";
            });
        });

        let modal_content_array = document.querySelectorAll('.modal-content');
        let modal_change_array = document.querySelectorAll('.modal-change');
        modal_change_array.forEach(function (modal_change) {
            modal_change.addEventListener('click', function (evt) {
                evt.preventDefault();
                let modal = modal_change.closest('.modal');
                let cur_content = modal_change.closest('.modal-content');
                cur_content.style.display = "none";
                Array.from(modal.children).forEach(function (content) {
                    content.style.display = "block";
                    if (content.getAttribute('name') === cur_content.getAttribute('name')) content.style.display = "none";            });
            });
        });

        let modal = document.querySelector('.modal');
        document.body.addEventListener('click', evt => {
            if (evt.target === modal) modal.style.display = 'none';
        });
    }
</script>
