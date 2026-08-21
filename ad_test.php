
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta charset="utf-8">
<title>Bootstrap, from Twitter</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- Le styles -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
    <![endif]-->
<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="bootstrap/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="bootstrap/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="bootstrap/ico/favicon.png">
<style type="text/css">
</style>
<link href="css/bootstrap-3.3.7.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script src="js/bootstrap-3.3.7.js"></script>
</head>
<body cz-shortcut-listen="true">
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="brand" href="#">Project name</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
  </div>
</div>
<div class="container">
  <h1>Добавление тестовых материалов</h1>
  <p>Use this document as a way to quick start any new project.<br>
    All you get is thismessage and a barebones HTML document.</p>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <form action="<?php echo $editFormAction; ?>" name="form" method="POST" enctype="multipart/form-data">
          <fieldset>
            <legend>Загрузка тестов</legend>
            <label for="test_name">Название теста</label>
            <input name="test_name" type="text" id="test_name" size="50">
            <label for="test_file">Файл теста</label>
            <input type="file" name="test_file" id="test_file">
            <label for="fimg">Рисунок</label>
            <input type="file" name="fimg" id="fimg">
            <label for="test_date">Дата создания</label>
            <input name="test_date" type="datetime" id="test_date" max="2020-01-01" min="2016-01-01" step="1">
          </fieldset>
          <input type="hidden" name="MM_insert" value="form">
          <input type="submit" name="submit" id="submit" value="Отправить">
        </form>
      </div>
    </div>
  </div>
</div>
<div class="container "></div>
<!-- /container -->
</body>
<div class="row">
  <div class="col-lg-4"></div>
  <div class="col-lg-4"></div>
  <div class="col-lg-4"></div>
</div>
</html>

