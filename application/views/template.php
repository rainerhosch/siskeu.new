<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">

    <title><?= $title ?></title>

    <meta name="description" content="Siskeu New">
    <meta name="author" content="STT Wastukancana">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">

    <link rel="shortcut icon" href="<?= base_url() ?>assets/proui/img/favicon/logo.png">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/proui/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/proui/css/plugins.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/proui/css/main.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/proui/css/themes.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/template/css/my.css">
    <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/proui/swal/dist/sweetalert2.min.css"> -->
    <link rel="stylesheet" href="<?= base_url('assets/template/sweetalert2/dist/sweetalert2.min.css') ?>">

    <!-- javascript -->
    <script src="<?= base_url() ?>assets/proui/js/vendor/modernizr.min.js"></script>
    <script src="<?= base_url() ?>assets/proui/js/vendor/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/proui/js/vendor/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/proui/js/plugins.js"></script>
    <script src="<?= base_url() ?>assets/proui/js/app.js"></script>
    <script src="<?= base_url() ?>assets/proui/js/pages/index.js"></script>
    <!-- <script src="<?= base_url() ?>assets/proui/swal/dist/sweetalert2.js"></script> -->
    <script src="<?= base_url() ?>assets/template/sweetalert2/dist/sweetalert2.min.js"></script>

    <script src="<?= base_url() ?>assets/proui/js/pages/tablesDatatables.js"></script>
    <script src="<?= base_url() ?>assets/proui/js/pages/moment.min.js"></script>

    <style>
        .containerX {
            min-height: 80vh;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: row;
            -ms-flex-direction: row;
            flex-direction: row;
            -webkit-flex-wrap: nowrap;
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-align-content: center;
            -ms-flex-line-pack: center;
            align-content: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .page404 {
            width: 400px;
            height: auto;
        }

        #tree {
            stroke: #59513C;
        }

        #wood-stump {
            stroke: #59513C;
            -webkit-animation: wood-stump 3s infinite ease-in-out;
            -moz-animation: wood-stump 3s infinite ease-in-out;
            -o-animation: wood-stump 3s infinite ease-in-out;
            animation: wood-stump 3s infinite ease-in-out;
        }

        @-webkit-keyframes wood-stump {
            0% {
                -webkit-transform: translate(100px)
            }

            50% {
                -webkit-transform: translate(50px);
            }

            100% {
                -webkit-transform: translate(100px);
            }
        }

        @-moz-keyframes wood-stump {
            0% {
                -moz-transform: translate(100px);
            }

            50% {
                -moz-transform: translate(50px);
            }

            100% {
                -moz-transform: translate(100px);
            }
        }

        @-o-keyframes wood-stump {
            0% {
                -o-transform: translate(100px);
            }

            50% {
                -o-transform: translate(50px);
            }

            100% {
                -o-transform: translate(100px);
            }
        }

        @keyframes wood-stump {
            0% {
                -webkit-transform: translate(100px);
                -moz-transform: translate(100px);
                -ms-transform: translate(100px);
                transform: translate(100px);
            }

            50% {
                -webkit-transform: translate(0px);
                -moz-transform: translate(0px);
                -ms-transform: translate(0px);
                transform: translate(0px);
            }

            100% {
                -webkit-transform: translate(100px);
                -moz-transform: translate(100px);
                -ms-transform: translate(100px);
                transform: translate(100px);
            }
        }


        #leaf {
            stroke: #59513C;
            -webkit-animation: leaf 7s infinite ease-in-out;
            -moz-animation: leaf 7s infinite ease-in-out;
            -o-animation: leaf 7s infinite ease-in-out;
            animation: leaf 7s infinite ease-in-out;
        }

        @-webkit-keyframes leaf {
            0% {
                -webkit-transform: translate(0, 70px)
            }

            50% {
                -webkit-transform: translate(0, 50px);
            }

            100% {
                -webkit-transform: translate(0, 70px);
            }
        }

        @-moz-keyframes leaf {
            0% {
                -moz-transform: translate(0, 70px);
            }

            50% {
                -moz-transform: translate(0, 50px);
            }

            100% {
                -moz-transform: translate(0, 70px);
            }
        }

        @-o-keyframes leaf {
            0% {
                -o-transform: translate(0, 70px);
            }

            50% {
                -o-transform: translate(0, 50px);
            }

            100% {
                -o-transform: translate(0, 70px);
            }
        }

        @keyframes leaf {
            0% {
                -webkit-transform: translate(0, 70px);
                -moz-transform: translate(0, 70px);
                -ms-transform: translate(0, 70px);
                transform: translate(0, 70px);
            }

            50% {
                -webkit-transform: translate(0px);
                -moz-transform: translate(0px);
                -ms-transform: translate(0px);
                transform: translate(0px);
            }

            100% {
                -webkit-transform: translate(0, 70px);
                -moz-transform: translate(0, 70px);
                -ms-transform: translate(0, 70px);
                transform: translate(0, 70px);
            }
        }

        #border {
            stroke: #59513C;
        }

        #Page {
            fill: #59513C;
        }

        #notFound {
            fill: #A7444B;
        }
    </style>
</head>

<body>
    <?php if ($title != 'Login Page') : ?>
        <div id="page-wrapper">
            <div id="page-container" class="sidebar-mini sidebar-visible-lg sidebar-no-animations">
                <?php $this->load->view('layout/sidebar'); ?>
                <div id="main-container">
                    <?php $this->load->view('layout/topbar'); ?>
                    <?php $this->load->view($content); ?>
                    <?php $this->load->view('layout/footer'); ?>
                <?php else : ?>
                    <?php $this->load->view($content); ?>
                <?php endif ?>
</body>

</html>