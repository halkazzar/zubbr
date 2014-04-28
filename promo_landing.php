<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head = "\n<script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";


    $page_title = '}:) › Фэйс контроль';

    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top_promo.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block_promo.inc";
    //require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     
?>

<div class="main">
    <div id="main-left">

        <div class="post">
            <div id="promo" class="myform"  style="background: #FAFAFA; border: 2px solid #cdc9c9;"> 
                <form name="promo_form" id="promo_form" action="/" method="post">   
                    <h1 class="left">Вбей промо код и узнай первым про Зуббр }:)</h1>
                    <div class="clearer">&nbsp;</div>

                    <p class="left"></p>

                    <div class="clearer">&nbsp;</div>

                    <label for="promo">Промо код:
                    </label>
                    <button type="submit" class="right" name="enter" tabindex="2">Вход</button>
                    <input type="text" class="promo nopadding right" id="promo" name="promo" tabindex="1"/>
                    <div class="clear"></div>
                    <p class="text-right nobmargin largest" id="nopromo"><a href="#">Получить промо код</a></p>

                    <div id="nopromo_div" class="hidden">
                        <div class="content-separator">&nbsp;</div>
                        <label for="yourself">Твое имя:
                        </label>
                        <input type="text" class="promo  right normargin" id="yourself" name="yourself" tabindex="3"/>
                        
                        <div class="clear"></div>
                        <label for="email">Твой email:
                        </label>
                        <input type="text" class="promo  right normargin" id="email" name="email" tabindex="4"/>
                        
                        <div class="clear"></div>
                        <button type="submit" class="right" name="request" tabindex="5">Запросить</button>
                    </div>
                    <div class="spacer"></div>
                </form> 
            </div>
        </div> 
        <div class="archive-separator"></div>
        <div class="clearer">&nbsp;</div>
    </div>


</div> 
<div class="clearer">&nbsp;</div>
</div>


<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_yandex_me.inc";
?>            
</body>
</html>