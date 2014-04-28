<div>
    <div id="basic" class="myform window2">
        <form name="login_form" id="login_form" action="/" method="post">   
            <h1 class="left">Вход</h1>
            <a href="#" class="close right" style="text-decoration: none">&nbsp;</a>
            <div class="clearer">&nbsp;</div>

            <p class="left">Введите свой логин и пароль</p>
            <p class="text-right"><a href="/users/registration/">Регистрация</a></p>
            <div class="clearer">&nbsp;</div>

            <label for="login">Логин

            </label>
            <input type="hidden" name="enter_user" value="Y" />
            <input type="text" class="login nopadding" id="login" name="login" tabindex="5"/> 
            <label for="password">Пароль
                <span class="passwordWarning small"><a href="/users/remindpass/">Напомнить пароль</a></span>
            </label>
            <input type="password" class="login nopadding" id="password" name="password" tabindex="6"/>
            <button type="submit" class="center" name="login_user" tabindex="7">Вход</button>
            <div class="spacer"></div>
        </form> 
    </div>           
            <div id="mask2">
            </div> 
</div>
<div id="site">
<div class="center-wrapper">
<div id="header">
 

    
    <div id="toolbar" >&nbsp;</div>
 





<div id="site-title">
    <div class="left">
        <h1><a href="/">Зуббр.кз</a><span> / }:)</span></h1>
    </div>

    <?php if(!$session->is_logged_in()):?>
        <div class="left">
            <table valign="MIDDLE" style="margin: 0px; padding: 0px; margin-left: 50px; width: 250px;">
                <tr style="float: left; margin: 0px; padding: 0px; width: 250px;">
                    <td style="float: left; margin: 0px; padding: 0px;">
                        <img src="/images/check.jpg" alt=""/>
                    </td>
                    <td style="text-transform: uppercase; font-size: 10pt; font-weight: bold; font-family: arial; padding-left: 0px;">
                        Обучение зарубежом
                    </td>
                </tr>
            </table>
            <table valign="MIDDLE" style="margin: 0px; padding: 0px; margin-left: 150px; width: 250px;">
                <tr style="float: left; margin: 0px; padding: 0px; width: 250px;">
                    <td style="float: left; margin: 0px; padding: 0px;">
                        <img src="/images/check.jpg" alt=""/>
                    </td>
                    <td style="text-transform: uppercase; font-size: 10pt; font-weight: bold; font-family: arial; padding-left: 0px;">
                        Ответы от выпускников
                    </td>
                </tr>
            </table>
            <table valign="MIDDLE" style="margin: 0px; padding: 0px; margin-left: 250px; width: 250px;">
                <tr style="float: left; margin: 0px; padding: 0px; width: 250px;">
                    <td style="float: left; margin: 0px; padding: 0px;">
                        <img src="/images/check.jpg" alt=""/>
                    </td>
                    <td style="text-transform: uppercase; font-size: 10pt; font-weight: bold; font-family: arial; padding-left: 0px;">
                        Тесты для поступающих
                    </td>
                </tr>
            </table>


        </div>

        <div class="right" style="margin-top: 10px;">
            
        </div>
        <?php endif;?>

    <div class="clearer">&nbsp;</div>
</div> 
<?php if (isset($_SESSION['error_code'])):?>
<div id="messagebox" class="hidden">
    <div class="center text-center"><?php echo_message($_SESSION['error_code']); unset($_SESSION['error_code']); ?></div>
</div>    
<?php endif;?>            
    