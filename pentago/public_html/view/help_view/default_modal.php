<?php
require_once '../component/login_component.php';
require_once '../component/signup_component.php';
?>
<div class="modal" id="modal-1">
    <form action="" class="modal-content" name="login_form"  method="post">
        <div class="modal_close">&times;</div>
        <div class="container">
            <h2>Login form</h2>
            <label for="login_username">Username</label>
            <input type="text" name="login_username">
            <label for="login_password">Password</label>
            <input type="password" name="login_password">
            <button type="submit" name = "login_submit">Login</button>
            <label style="font-weight: normal">
                <input type="checkbox" checked="checked" name="remember"> I agree to use cookie at this website
            </label>
        </div>

        <div class="container forgot-container">
            <div class="forgot1">Don't have an <a href="" class="modal-change">account</a>?</div>
            <div class="forgot1">Forgot <a href="">password</a>?</div>
        </div>
    </form>
    <form action="" name="signup_form" class="modal-content" style="display: none" method="post">
        <div class="modal_close">&times;</div>
        <div class="container">
            <h2>Sign Up form</h2>
            <label for="signup_username">Username</label>
            <input type="text" name="signup_username">
            <label for="signup_mail">Email</label>
            <input type="email" name="signup_mail">
            <label for="signup_password">Password</label>
            <input type="password" name="signup_password">
            <button type="submit" name="signup_submit">Sign Up</button>
        </div>

        <div class="container forgot-container">
            <div class="forgot1">You have an <a href="" class="modal-change">account?</a></div>
            <div class="forgot1">Forgot <a href="">password?</a></div>
        </div>
    </form>
</div>