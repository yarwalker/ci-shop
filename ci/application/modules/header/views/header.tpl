<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>{page_title}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />

    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

    <!--link rel="stylesheet" type="text/css" href="{SITEURL}/assets/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" /-->

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link id="page_favicon" href="{SITEURL}/assets/images/favicon.ico" rel="icon" type="image/x-icon" />  
    <!--script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-1.7.2.min.js"></script-->

    <link rel="stylesheet" type="text/css" href="{SITEURL}/assets/css/styles.css" />

</head>
<body>
    <div id="wrapper">
        <div id="header">
            <a href="/" id="logo"></a>
            <div id="phones">
                <span>телефоны: (4852)</span>
                <span>200-859<br />200-897</span>
            </div> 
            <div id="top_search">
                
                <form action="/catalog/search" method="post">
                    <div id="search_text"><input type="text" id="st" name="q" value="поиск по каталогу" size="15" maxlength="50" /></div>
                    <div id="s_button"><input type="image" src="{SITEURL}/assets/images/search_btn.png" /></div>
                </form>
            </div>
            <div id="cabinet">
                <span>Кустик Константин Константинопольский</span>
                <a href="#">Личный кабинет</a>&nbsp;&nbsp;X&nbsp;<a href="#">Выйти</a>
            </div>
            <!--div id="mini_cart">
                <span><a href="#">В корзине</a></span>
                26 товаров на 45 589.34 <i class="rubl">a</i>
            </div-->
            {minicart}

            <div id="top_menu">
                <a href="/">Главная</a>
                <a href="/">Услуги</a>
                <a href="/">О компании</a>
                <a href="/">Отзывы</a>
                <a href="/">Контакты</a>
            </div> 
        </div>

        <div id="container">