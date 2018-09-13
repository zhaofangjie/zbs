<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"D:\xampp\htdocs\zbs\public/../application/index\view\index\index.html";i:1536830640;}*/ ?>
<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $site['name']; ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/index.css" rel="stylesheet">

        <!-- Plugin CSS -->
        <link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="//cdn.bootcss.com/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="//cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
            <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body id="page-top">

        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-menu">
                        <span class="sr-only">Toggle navigation</span><i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand page-scroll" href="#page-top"><img src="/style/images/logo.png" style="width:200px;" alt=""></a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse-menu">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" target="_blank"><?php echo __('Home'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Store'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Services'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Download'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Demo'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Donation'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Forum'); ?></a></li>
                        <li><a href="#" target="_blank"><?php echo __('Docs'); ?></a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>

        <header>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-content">
                            <div class="header-content-inner">
                                <h1>知客易汇</h1>
                                <h3>专业的投资顾问平台</h3>
                                <a href="<?php echo url('admin/index/login'); ?>" class="btn btn-warning btn-xl page-scroll"><?php echo __('Go to Dashboard'); ?></a>
                                <a href="<?php echo url('index/user/index'); ?>" class="btn btn-outline btn-xl page-scroll"><?php echo __('Go to Member center'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="features" class="features">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="section-heading">
                            <h2><?php echo __('Features'); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-user text-primary"></i>
                                        <h3><?php echo __('Auth'); ?></h3>
                                        <p class="text-muted"><?php echo __('Auth tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-screen-smartphone text-primary"></i>
                                        <h3><?php echo __('Responsive'); ?></h3>
                                        <p class="text-muted"><?php echo __('Responsive tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-present text-primary"></i>
                                        <h3><?php echo __('Languages'); ?></h3>
                                        <p class="text-muted"><?php echo __('Languages tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-layers text-primary"></i>
                                        <h3><?php echo __('Module'); ?></h3>
                                        <p class="text-muted"><?php echo __('Module tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-docs text-primary"></i>
                                        <h3><?php echo __('CRUD'); ?></h3>
                                        <p class="text-muted"><?php echo __('CRUD tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-puzzle text-primary"></i>
                                        <h3><?php echo __('Extension'); ?></h3>
                                        <p class="text-muted"><?php echo __('Extension tips'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="cta-content">
                <div class="container">
                    <h2>原油期货<br>大师直播</h2>
                    <a href="index/room" class="btn btn-outline btn-xl page-scroll">直播室</a>
                </div>
            </div>
            <div class="overlay"></div>
        </section>

        <footer>
            <div class="container">
                <p>&copy; 2017-2018 <a href="#" target="_blank">zhike</a>. All Rights Reserved.</p>
                <ul class="list-inline">
                    <li>
                        <a href="">ICP:</a>
                    </li>
                    <li>
                        <a href="https://shang.qq.com/wpa/qunwpa?idkey=00790ef1ed399b248ac6c4c77c1b8ca8c54d9bad3e75cda8b92844eeb4f8229b"><?php echo __('QQ group'); ?></a>
                    </li>
                </ul>
            </div>
        </footer>

        <!-- jQuery -->
        <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="//cdn.bootcss.com/jquery-easing/1.4.1/jquery.easing.min.js"></script>



    </body>

</html>