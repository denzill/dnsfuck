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
        <link rel="shortcut icon" href="../assets/ico/favicon.ico">
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
                            <li class="active"><a href="#">Списки доменов</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <?= $this->html; ?>
            <div id='debug'></div>
            <div class="tabbable">
                <ul class="nav nav-tabs" id="domainTabs">
                    <li class="active"><a href="#auc_domains" data-toggle="tab">Освободившиеся</a></li>
                    <li><a href="#exp_domains" data-toggle="tab">Освобождающиеся</a></li>
                </ul>
                <div class="tab-content">
                    <?= $content->getTab('auc_domains') ?>
                    <?= $content->getTab('exp_domains') ?>
                </div>
            </div>
        </div> <!-- /container -->
        <? include 'inc/updateDialog.php'; ?>

        <!-- Le javascript ================================================= -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="js/jquery.min.js?<?= $date ?>"></script>
        <script src="js/bootstrap.min.js?<?= $date ?>"></script>
        <script src="js/main.js?<?= $date ?>"></script>
    </body>
</html>
