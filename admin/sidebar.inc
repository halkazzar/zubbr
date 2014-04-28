<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php'; ?>

<div id="sidebar">
    <ul class="sideNav">
        <li><a href="/admin/page/dashboard/" <?php if ($page == 'dashboard') echo 'class="active"'?>>Дашборд</a></li>
        <li><a href="/admin/page/log/" <?php if ($page == 'log') echo 'class="active"'?>>Лог</a></li>
        <li>&nbsp;</li>
        <li><a href="/admin/page/users/" <?php if ($page == 'users') echo 'class="active"'?>>Пользователи</a></li>
        <li><a href="/admin/page/degrees/" <?php if ($page == 'degrees') echo 'class="active"'?>>Квалификации</a></li>
        <li><a href="/admin/page/studyareas/" <?php if ($page == 'studyareas') echo 'class="active"'?>>Предметные области</a></li>
        <li><a href="/admin/page/locations/" <?php if ($page == 'locations') echo 'class="active"'?>>Расположения</a></li>
        <li><a href="/admin/page/scholarships/" <?php if ($page == 'scholarships') echo 'class="active"'?>>Стипендии</a></li>
        <li>&nbsp;</li>
        
        <li><a href="/admin/page/organizations/" <?php if ($page == 'organizations') echo 'class="active"'?>>Ассоциации</a></li>
        <li>&nbsp;</li>
        
        <li><a href="/admin/page/universities/" <?php if ($page == 'universities') echo 'class="active"'?>>ВУЗы</a></li>
        <li><a href="/admin/page/slides/" <?php if ($page == 'slides') echo 'class="active"'?>>Слайды к ВУЗам</a></li>
        <li><a href="/admin/page/specialities/" <?php if ($page == 'specialities') echo 'class="active"'?>>Специальности в ВУЗах</a></li>
        <li><a href="/admin/page/universities_relations/" <?php if ($page == 'universities_relations') echo 'class="active"'?>>Люди и ВУЗы</a></li>
        <li>&nbsp;</li>
        
        <li><a href="/admin/page/questions/" <?php if ($page == 'questions') echo 'class="active"'?>>Вопросы</a></li>
        <li><a href="/admin/page/answers/" <?php if ($page == 'answers') echo 'class="active"'?>>Ответы</a></li>
        <li>&nbsp;</li>
        
        <li><a href="/admin/page/promotion/" <?php if ($page == 'promotion') echo 'class="active"'?>>Промоушн и статистика</a></li>
        <li><a href="/admin/page/news/" <?php if ($page == 'news') echo 'class="active"'?>>Новостной блок</a></li>
        <li><a href="/admin/page/subscriptions/" <?php if ($page == 'subscriptions') echo 'class="active"'?>>Подписка</a></li>
        <li><a href="/admin/page/jobpostings/" <?php if ($page == 'jobpostings') echo 'class="active"'?>>Объявления о работе</a></li>
        <li><a href="/admin/page/tags/" <?php if ($page == 'tags') echo 'class="active"'?>>Теги</a></li>
        
        <li>&nbsp;</li>
        <li><a href="/admin/page/tests/" <?php if ($page == 'tests') echo 'class="active"'?>>Тесты</a></li>
        <li><a href="/admin/page/testquestions/" <?php if ($page == 'testquestions') echo 'class="active"'?>>Вопросы тестов</a></li>
        
    </ul>
    <!-- // .sideNav -->
</div> 
<!-- // #sidebar -->

<!-- h2 stays for breadcrumbs -->
<h2><?php switch ($page) {
            case 'users':
                echo 'Пользователи';
                break;

            case 'universities':
                echo 'Университеты';
                break;

            case 'slides':
                echo 'Слайды';
                break;

            case 'tests':
                echo 'Тесты';
                break;
            case 'testquestions':
                echo 'Вопросы тестов';
                break;

            case 'locations':
                echo 'Расположения';
                break;
            case 'scholarships':
                echo 'Стипендии';
                break;
            case 'degrees':
                echo 'Квалификации';
                break;
            case 'studyareas':
                echo 'Предметные области';
                break;
            case 'specialities':
                echo 'Специальности';
                break;
            case 'universities_relations':
                echo 'Люди и Универы';    
                break;
            case 'questions':
                echo 'Вопросы';    
                break;
            case 'send_question':
                echo 'Вопросы › Выбор получателей';    
                break;
            case 'answers':
                echo 'Ответы';    
                break;
            case 'send_answer':
                echo 'Ответы › Выбор получателей';    
                break;
            case 'promotion':
                echo 'Промоушн и Статистика';    
                break;
            case 'news':
                echo 'Новостной блок';    
                break;
            case 'tags':
                echo 'Теги';    
                break;
            case 'jobpostings':
                echo 'Объявления о работе';    
                break;
            case 'subscriptions':
                echo 'Подписка';    
                break;
            case 'organizations':
                echo 'Ассоциации';    
                break;
            case 'dashboard':
                echo 'Дашборд';    
                break;
            case 'log':
                echo 'Лог';    
                break;
        }
?></h2>
                