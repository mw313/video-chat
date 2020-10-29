<div class="modal fade" id="modalLRFormDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <?=$title ?>
            </div>
            <div class="modal-body">
                <?=$message ?>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>

<script>
    $('#modalLRFormDemo').modal('show');
</script>