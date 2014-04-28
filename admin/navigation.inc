        <!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
        <h1><a href="#"><span>Transdmin Light</span></a></h1>
        
        <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
        <ul id="mainNav">
            <li><a href="/admin/page/dashboard/" class="active">Админка</a></li> <!-- Use the "active" class for the active menu item  -->
            <li class="logout">
            <form action="/" method="POST">    
            <input type="hidden" name="logout" value="true">
            <input id="logout" type="submit" class="hidden" value="Выход" >
        </form>
        <a href="#" onclick="document.getElementById('logout').click()">Выйти</a></li>
        </ul>
        <!-- // #end mainNav -->
