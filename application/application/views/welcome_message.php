<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>API  Area </title>

        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="<?php echo base_url(); ?>css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="<?php echo base_url(); ?>css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="<?php echo base_url(); ?>font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">API AREA NO ACCESS</h3>
                        </div>
                        <?php /* ?>
                        <div class="panel-body">
                            <form role="form" method="POST" id="admin_login_form" action="<?php echo base_url() ?>welcome/login">
                               
                                    <?php
                                    if (validation_errors ()) {?>
                                         <div class="alert alert-danger"  id="display_error" >
                                       <?php echo validation_errors();?>
                                          </div>
                                    <?php }
                                    if ($this->session->flashdata('error')){?>
                                 <div class="alert alert-danger"  id="display_error" >
                                     <?php   echo $this->session->flashdata('error'); ?>
                                 </div>
                                    <?php }?>
                               

                                <fieldset>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">@</span>
                                        <input class="form-control" placeholder="E-mail" name="username" id="username" type="email" autofocus>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">&nbsp;*&nbsp;</span>
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input  name="remember" type="checkbox" value="Remember Me" style="display: none">
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <input type="submit" id="submit" name="submit" value="Login" class="btn btn-lg btn-success btn-block">
                                </fieldset>
                            </form>
                        </div>
                        <?php */ ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery Version 1.11.0 -->
        <script src="<?php echo base_url(); ?>js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="<?php echo base_url(); ?>js/plugins/metisMenu/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="<?php echo base_url(); ?>js/sb-admin-2.js"></script>

    </body>

</html>
