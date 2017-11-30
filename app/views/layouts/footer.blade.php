<!-- Please Wait Modal -->
<div class="modal fade" id="pleaseWaitDialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-vertical-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Procesando...</h1>
            </div>
            <div class="modal-body">
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span class="sr-only">45% Complete</span>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./ Please Wait Modal -->

<!-- jQuery -->
<script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="{{ URL::asset('bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>

<!-- Morris Charts JavaScript -->
{{-- <script src="{{ URL::asset('bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ URL::asset('bower_components/morrisjs/morris.min.js') }}"></script>
<script src="{{ URL::asset('js/morris-data.js') }}"></script> --}}

<!-- Custom Theme JavaScript -->
<script src="{{ URL::asset('dist/js/sb-admin-2.js') }}"></script>

<!-- DataTables JavaScript -->
<script src="{{ URL::asset('bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>

<!-- JQuery Validate -->
<script src="{{ URL::asset('js/vendors/validate/validate.js') }}"></script>
<script src="{{ URL::asset('js/vendors/validate/additional-methods.min.js') }}"></script>

<!-- Bootstrap DatePicker -->
<script src="{{ URL::asset('js/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('js/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}" charset="UTF-8"></script>

<script>
	$(document).ready(function() {
		var today = new Date();
	    /*$('#dataTables-example').DataTable({
	            responsive: true
	    });*/

	    /*$('.input-daterange').datepicker({
			format: 'dd/mm/yyyy',
			todayBtn: "linked",
			language: "es",
			todayHighlight: true,
			startDate: new Date()
		});*/

	});
</script>