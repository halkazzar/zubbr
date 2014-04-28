<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta http-equiv="last-modified" content="2011-1-26" />
    <meta name="alexaVerifyID" content="b2br_uGffYsD3lHKoHYbseIxkMo" />
    <link rel="stylesheet" type="text/css" href="/stylesheets/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />
    <script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js' ></script>
    <script type='text/javascript' src='/javascripts/promo.js' ></script>
    <script type='text/javascript' src='/javascripts/login.js' ></script>
    <script type='text/javascript' src='/javascripts/messagebox.js' ></script>
    <?php if (isset($include_in_head)) echo $include_in_head?>


    <?php if (!empty($page_title)):?>
    <title><?php echo $page_title?></title>
    <?php else:?>
    <title>}:) - Зазубрим все</title>
    <?php endif;?>

    <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_google_an.inc";
        //require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_liveinternet.inc";


    ?>
</head>

<body id="top">

<div id="network" style="height: 31px;">
    <div class="center-wrapper">
        <div class="left"><a href="/">www.ZUBBR.kz</a> <span class="quiet">beta</span><span class="text-separator"> |</span><span class="quiet"><?php echo date_translate("today","top")?></span></div>
        <div class="right">
            <ul class="tabbed" id="network-tabs">
                <?php if ($session->is_logged_in()):?>
                <li>
                    <a style="font-weight: bold; text-decoration: none"><span style="color: black">&nbsp;<?php echo User::find_by_id($session->user_id)->login?>&nbsp;</span></a>
                </li>
                <li>
                    <form action="/" method="POST" class="hidden">    
                    <input type="hidden" name="logout" value="true">
                    <input type="submit" class="button hidden" id="logout" value="Выход">
                    </form>
                    <a onclick="document.getElementById('logout').click()" href="#" id='logout_link' style="font-weight: bold; text-decoration: none"><span style="border-bottom: 1px dotted; color: black">&nbsp;Выйти&nbsp;</span></a>
                </li>    
                    <?php else:?>
                <li>
                    <a href="#" id='login_link' style="font-weight: bold; text-decoration: none"><span style="border-bottom: 1px dotted; color: black">&nbsp;Войти&nbsp;</span></a>
                </li>
                <?php endif;?>
                <li><a style="text-decoration: none;"><?php echo TOP_FOLLOW_US?></a></li>
                <li class="current-tab-first"><a href="http://twitter.com/zubbrkz" target="blank"><img src="http://twitter-badges.s3.amazonaws.com/t_small-a.png" alt="Следуйте за нами в Twitter"/></a></li>
                <li class="current-tab"><a href="#"><img src="/images/icon-vkontakte.gif" alt="Следуйте за нами вКонтакте"/></a></li>
                <li class="current-tab"><a href="http://www.facebook.com/profile.php?id=100001984047974" target="blank"><img src="/images/icon-fb.png" alt="Наша страница на Facebook"/></a></li>
                <li class="current-tab"><a href="http://my.mail.ru/mail/zubbrkz" target="blank"><img src="/images/icon-mailru.png" alt="Следуйте за нами в МойМир"/></a></li>
            </ul>
            
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
</div>
