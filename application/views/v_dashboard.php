<style>
    .progress {
        overflow: hidden;
        height: 20px;
        margin-bottom: 0px;
        background-color: #cdcdcd;
        border-radius: 4px;
    }
    .table thead>tr>th {
        font-size: 14px;
        font-weight: 600;
    }

    .row {
        margin-bottom: 5px;
    }

    table {
        background-color: #ffffff;
    }
    .heder-table{
        display: flex;
    }
    .leftHeader{
        /* margin-top: 3px;
        margin-left: 60%; */
        position: absolute;
        right: 35px;
        margin: 3px;
    }
    
</style>
<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <?php // $this->load->view('layout/stat_row'); 
    ?>
    <?php $this->load->view('layout/row_sync_data'); ?>
    <!-- End -->
    <!-- <script>
        var getUrl = window.location;
        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
        Swal.fire({
            title: 'Antosan sakedap . . .',
            position: 'top',
            width: 300,
            allowOutsideClick: false,
            showConfirmButton: false,  
            color: '#716add',
            // background: `#fff url(${baseUrl}/assets/images/trees.png)`,
            backdrop: `
                rgba(0,0,123,0.4)
                url('${baseUrl}/assets/img/nyan-cat-2.gif')
                center
                no-repeat
            `
        })
    </script> -->
</div>
<!-- END Page Content -->