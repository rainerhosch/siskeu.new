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

    <div class="block">
        <div class="block-title">
            <div class="heder-table">
                <h2>List VA<span class="smt_aktif"></span></h2>
            </div>
        </div>
        <div>
            <a href="" class="btn btn-info btn-success btn-sm" style="margin-bottom:1rem;">Create VA</a>
        </div>
        <div class="table-responsive">
            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">No</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">No VA</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Nama</br>Mahasiswa</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Amount</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Billing</br>Type</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Expired</br>Date</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Jenis</br>Pembayaran</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Kode</br>Pembayaran</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Giro</br>Tujuan</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">Deskripsi</th>
                    </tr>
                </thead>
                <tbody id="data_list_va_tbody">
                </tbody>
                <tfoot id="data_list_va_tfoot"></tfoot>
            </table>
        </div>
    </div>
    <!-- <script>
    </script> -->
</div>
<!-- END Page Content -->