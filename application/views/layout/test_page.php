<div id="page-content">
    <!-- Blank Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-brush"></i>Blank<br><small>A clean page to help you start!</small>
            </h1>
        </div>
    </div>
    <!-- Example Block -->
    <div class="block">
        <!-- Example Title -->
        <div class="block-title">
            <h2>Block Title</h2>
        </div>
        <!-- END Example Title -->
        <!-- Example Content -->
        <p><a class="btn btn-xs btn-info btn_sync">Sync</a></p>
        <!-- END Example Content -->
    </div>
    <script>
        $(document).ready(function() {
            $('.btn_sync').on('click', function(){
                $.ajax({
                    type: "POST",
                    url: '<?= base_url() ?>migrasitrxtotg/getTrxLastSmt',
                    dataType: "json",
                    success: function(response) {
                        console.log(response)
                    }
                })
            })
        })
    </script>
</div>