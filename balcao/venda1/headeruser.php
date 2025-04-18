<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Bar | Dashboard</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  

  <!-- jQuery 3 -->

  <script src="../bower_components/jquery/dist/jquery.min.js"></script>

  <!-- Bootstrap 3.3.7 -->

  <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- AdminLTE App -->

  <script src="../dist/js/adminlte.min.js"></script>

  <script src="../bower_components/sweetalert/sweetalert.js"></script>

  <!-- DataTables -->

  <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

  <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>



  <script src="../Chart.js-2.8.0/dist/Chart.min.js"></script>

  



  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">

  

  <!-- Select2 -->

  <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">

  

  <!-- Font Awesome -->

  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">

  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter

        page. However, you can choose any other skin. Make sure you

        apply the skin class to the body tag so the changes take effect. -->

        <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">





        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->



<!-- Google Font -->

<link rel="stylesheet"

href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<!-- DataTables -->

<link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">



<!-- daterange picker -->

<link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">

<!-- bootstrap datepicker -->

<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">   





<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- bootstrap datepicker -->

<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<!-- bootstrap color picker -->     



<!-- iCheck for checkboxes and radio inputs -->

<link rel="stylesheet" href="../plugins/iCheck/all.css">  



<!-- iCheck 1.0.1 -->

<script src="../plugins/iCheck/icheck.min.js"></script>                         



<!-- Select2 -->

<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>       

</head>



<!--

BODY TAG OPTIONS:

=================

Apply one or more of the following classes to get the

desired effect

|---------------------------------------------------------|

| SKINS         | skin-blue                               |

|               | skin-black                              |

|               | skin-purple                             |

|               | skin-yellow                             |

|               | skin-red                                |

|               | skin-green                              |

|---------------------------------------------------------|

|LAYOUT OPTIONS | fixed                                   |

|               | layout-boxed                            |

|               | layout-top-nav                          |

|               | sidebar-collapse                        |

|               | sidebar-mini                            |

|---------------------------------------------------------|

-->

<body class="hold-transition skin-blue sidebar-mini">

  <div class="wrapper">



    <!-- Main Header -->

    <header class="main-header">



      <!-- Logo -->

      <a href="index2.html" class="logo">

        <!-- mini logo for sidebar mini 50x50 pixels -->

        <span class="logo-mini"><b>QA</b>NI</span>

        <!-- logo for regular state and mobile devices -->

        <span class="logo-lg"><b>QANI</b>-BAR</span>

      </a>



      <!-- Header Navbar -->

      <nav class="navbar navbar-static-top" role="navigation">

        <!-- Sidebar toggle button-->

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">

          <span class="sr-only">Toggle navigation</span>

        </a>

        <!-- Navbar Right Menu -->

        <div class="navbar-custom-menu">

          <ul class="nav navbar-nav">

            <!-- Messages: style can be found in dropdown.less-->

            <!-- User Account Menu -->

            <li class="dropdown user user-menu">

              <!-- Menu Toggle Button -->

              <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                <!-- The user image in the navbar-->



                <!-- hidden-xs hides the username on small devices so only the image appears. -->

                <p class="fa fa-user-circle-o"></p>

                <span class="hidden-xs"><?php echo $_SESSION['username'];?></span>

              </a>

              <ul class="dropdown-menu">

                <!-- The user image in the menu -->

                <li class="user-header">



                  <p>

                    <?php echo $_SESSION['useremail'];?>



                  </p>

                </li>

                <!-- Menu Body -->



                <!-- Menu Footer-->

                <li class="user-footer">

                  <div class="pull-left">

                    <a href="changepassword.php" class="btn btn-default btn-flat">Alterar Senha</a>

                  </div>

                  <div class="pull-right">

                    <a href="../logout.php" class="btn btn-default btn-flat">Sair</a>

                  </div>

                </li>

              </ul>

            </li>

            <!-- Control Sidebar Toggle Button -->



          </ul>

        </div>

      </nav>

    </header>

    <!-- Left side column. contains the logo and sidebar -->

    <aside class="main-sidebar">



      <!-- sidebar: style can be found in sidebar.less -->

      <section class="sidebar">



        <!-- Sidebar user panel (optional) -->

        <div class="user-panel">



        </div>

        <ul class="sidebar-menu" data-widget="tree">

          <li><a href="mesa.php"><img src="../images/icons8-chair.png"><span>Cadeiras</span></a></li>


          <li class="treeview">

          <a href="#">

            <img src="../images/icons8-tear_off_calendar.png">

            <span>Relatorio</span>

            <span class="pull-right-container">

              <i class="fa fa-angle-left pull-right"></i>

            </span>

          </a>

          <ul class="treeview-menu">

          <li><a href="diario.php"><i class="fa fa-calendar"></i> <span>Diario</span></a>

        </li>

        

      </ul>

    </li>
    <li class="treeview">
          <a href="#"><i class="fa fa-key" style="font-size:18px;color:red"></i>
            <span>Fecho de conta</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
			  <ul class="treeview-menu">
			  <li><a href="fecho.php"><i class="fa fa-info-circle" style="font-size:18px;color:red"></i><span>Diario</span></a></li>
			</ul>
			</li>

        </ul>

        <!-- /.sidebar-menu -->

      </section>

      <!-- /.sidebar -->

    </aside>

