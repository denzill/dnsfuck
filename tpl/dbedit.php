<?php
/**
 * <Create your comment here>
 *
 * $Revision: $
 * $Id: $
 * $Date:  $
 *
 * @Author: $Author: $
 * @version $Revision: $
 */
$date = time();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>DNSFUCK</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
        <style>
            body {
                padding-top: 40px; /* 60px to make the container go all the way to the bottom of the topbar */
            }
        </style>
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="dnsfuck.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="#">DNSFUCK</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="?mode=main">Списки доменов</a></li>
                            <li class="active"><a href="?mode=dbedit">База данных</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <form class='form-horizontal'>
                <legend>Редактирование базы </legend>
                <textarea name='sql' id='sql_text' class='span12' rows='6' placeholder="SQL запрос"></textarea>
                <span class="help-block">Сверху пишем запрос - снизу смотрим результат.</span>
                <button id='btn_submit' type="submit" class="btn btn-primary">Выполнить</button>
            </form>
            <div id='result'>
            </div>
        </div> <!-- /container -->

        <!-- Le javascript ================================================= -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="js/jquery.min.js?<?= $date ?>"></script>
        <script src="js/bootstrap.min.js?<?= $date ?>"></script>
        <script src="js/dbedit.js?<?= $date ?>"></script>
    </body>
</html>
