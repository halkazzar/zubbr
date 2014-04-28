<?php
    //require_once needed class which extends all others
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    
        //Resetting the password
    if(isset($_POST['reset_pass'])){
        if(isset($_POST['a']) && isset($_POST['b'])){
        $user = User::find_by_id($_POST['a']);
        if ($user->pass_remind == $_POST['b']){
            $user->password = md5($_POST['reg_password']);
            $user->pass_remind = 0;
            $user->save();
            
            $error_code = 0;
            redirect_to("/");    
        }
        else{
            $error_code = -1;
            //LOG HERE that someone tries to hack
            redirect_to("/");
        }
    }
    }
    
    
    if(isset($_GET['user_id']) && isset($_GET['h'])){
        $user = User::find_by_id($_GET['user_id']);
        if ($user->pass_remind == $_GET['h']){
            $found = true;    
        }
        else{
            $error_code = 0;
            //LOG HERE that someone tries to hack
            redirect_to("/");
        }
    }    
    
    
    

    // template: iclude top part
    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/role_selector.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/remindpass_index.js' ></script>";
    
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Восстановление пароля ';
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";

    //Navigation block
    $sub_navigation = "registration";
    $current_tab = "remindpass";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";

    

?>


<div class="main" id="main-two-columns-reg">
    <div class="left" id="main-left-reg">
        <h1>Восстановление пароля</h1>

        <div id="basic" class="myform">
            <form name="reg" id="reg" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">   
                <h1>Задайте пароль</h1>
                <p>Введите новый пароль.</p>
                <input name="a" type="hidden" value="<?php if($found) echo $_GET['user_id']?>"></input>
                <input name="b" type="hidden" value="<?php if($found) echo $_GET['h']?>"></input>
                
                <label>Пароль
                    <span class="passwordWarning small"></span>
                </label>
                <input class="reg_password" name="reg_password" type="password" tabindex="2"/>  
                <div class="passwordCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="passwordWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>
               
                <button type="submit" class="center" name="reset_pass">Сохранить</button>
                <div class="spacer"></div>
            </form> 
        </div> 
    </div>

    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/remindpass_right_sidebar.inc"; ?>
    <div class="clearer">&nbsp;</div>
</div>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc"; ?>
