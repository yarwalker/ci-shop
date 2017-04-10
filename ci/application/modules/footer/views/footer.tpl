        </div> <!-- div id="container" -->
        
        <div class="buffer"></div>
    </div> <!-- div id="wrapper" -->
    
    <!--div class="clear"></div-->
    <div id="footer">
    <div id="f_left">
        ИТ-Стандарт<br />
        тел.: (4852) 200-857<br />
        <a href="mailto:sales@it-standart76.ru">sales@it-standart76.ru</a>
    </div>
    <div id="f_right">       
        <div id="bottom_menu">
            <a href="/">Главная</a>
            <a href="/">Услуги</a>
            <a href="/">Каталог</a>
            <a href="/">О компании</a>
            <a href="/">Отзывы</a>
            <a href="/">Контакты</a>
        </div>
        <span>&copy; 2014. ИТ-Стандарт</span>
    </div>
    </div>
    <div id="dialog-form" title="Оставить отзыв">
        <p class="validateTips">Все поля формы должны быть заполнены.</p>
 
        <form>
            <fieldset>
                <label for="name">ФИО</label><br/>
                <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" /><br/>
                <label for="org_name">Email</label><br/>
                <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" /><br/>
                <label for="org_name">Организация</label><br/>
                <input type="text" name="org_name" id="org_name" value="" class="text ui-widget-content ui-corner-all" /><br/>
                <label for="resp_text">Отзыв</label><br/>
                <!--input type="text" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" /-->
                <textarea id="resp_text" name="resp_text" class="text ui-widget-content ui-corner-all"></textarea>
                          
            </fieldset>
        </form>
    </div>



        <script type="text/javascript" src="{SITEURL}/assets/js/jquery-ui-1.10.1.custom.js"></script>

        <link rel="stylesheet" href="{SITEURL}/assets/css/jquery-ui-1.10.1.custom.min.css" />

        <script type="text/javascript" src="{SITEURL}/assets/js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="{SITEURL}/assets/js/jquery.jcarousel.js"></script>

        <script type="text/javascript" src="{SITEURL}/assets/js/script.js"></script>

        <script>
            $(function(){
                $(".mouseWheel .jCarouselLite").jCarouselLite({
                    mouseWheel: false,
                    btnNext: ".next",
                    btnPrev: ".prev",
                    visible: 3
                });
            });
        </script>
        <script type="text/javascript" src="{SITEURL}/assets/js/typeface-0.15.js"></script>
        <script type="text/javascript" src="{SITEURL}/assets/js/myriad_pro_regular.typeface.js"></script>
</body>
</html>                      
