<!-- Page content -->
<div id="page-content">
    <!-- Page Header -->
    <!-- <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-brush"></i>Page Title<br><small>Subtitle</small>
            </h1>
        </div>
    </div> -->
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <?php //$this->load->view('layout/top_nav'); 
    ?>
    <!-- END Page Header -->

    <!-- Box -->

    <?php // $this->load->view('layout/stat_row'); ?>
    <?php $this->load->view('layout/row_sync_data'); ?>
    <!-- End -->


    <!-- Example Block -->
    <div class="block">
        <div class="block-title">
            <!-- <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" data-toggle="tooltip" title="Settings"><i class="fa fa-cog"></i></a>
                <div class="btn-group btn-group-sm">
                    <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Options"><span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                        <li>
                            <a href="javascript:void(0)"><i class="gi gi-cloud pull-right"></i>Simple Action</a>
                            <a href="javascript:void(0)"><i class="gi gi-airplane pull-right"></i>Another Action</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-wrench fa-fw pull-right"></i>Separated Action</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <h2>Data Semester <span id="smt_aktif"></span></h2>
        </div>
        <div class="table-responsive">
                            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">TAHUN ANGKATAN</th>
                                        <th class="text-center">TOTAL MAHSISWA</th>
                                        <th class="text-center">SUDAH MELAKUKAN PEMBAYARAN SPP</th>
                                        <th class="text-center">PERSENTASE</th>
                                    </tr>
                                </thead>
                                <tbody id="data_pembayaran_angkatan_modal">
                                </tbody>
                                <tfoot id="data_pembayaran_angkatan_tfoot"></tfoot>
                            </table>
                        </div>
    </div>
    <!-- END Example Block -->
</div>
<script>
    $.ajax({
      type: "POST",
      url: "masterdata/getDataPembayaranDashboard",
      dataType: "json",
      success: function (response) {
        console.log(response);
        $('#smt_aktif').append(response.smt_aktif);
        html=``;
        let no = 1;
        $.each(response.data, function (i, val) {
            html += `<tr>`;
            html += `<td class="text-center">${no}</td>`;
            html += `<td class="text-center">${val.tahun_masuk}</td>`;
            html += `<td class="text-center">${val.jml_mhs}</td>`;
            html += `<td class="text-center">${val.trx}</td>`;
            //   html += `<td class="text-center">${Math.ceil((val.trx / val.jml_mhs)*100) }%</td>`;
            html +=`<td class="text-center">`;
            html +=`<div class="progress">
            <div class="progress-bar" role="progressbar" style="width: `+Math.ceil((val.trx / val.jml_mhs)*100)+`%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">`+Math.ceil((val.trx / val.jml_mhs)*100)+`%</div>
            </div>`;
            html +=`</td>`;
            no++;
        });
        $("#data_pembayaran_angkatan_modal").html(html);

      }
    })
</script>
<!-- END Page Content -->