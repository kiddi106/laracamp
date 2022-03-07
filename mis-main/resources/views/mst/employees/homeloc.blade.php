<form method="POST" action="{{ route('mst.employee.update') }}">
    @csrf
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="current_password" class="col-md-3 col-form-label text-md-right">{{ __('Latitude Position') }}</label>

                    <div class="col-md-6">
                        <label class="col-form-label" style="font-weight:normal">{{ $loc ?? ''->position }}</label>
                    </div>
                    
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Longitude Position') }}</label>

                    <div class="col-md-6">
                        <input value="" class="loc" name="loc">
                        <label class="col-form-label" style="font-weight:normal">{{ $loc ?? ''->position }}</label>
                    </div>

                </div>

                <div class="modal fade" id="modal-location" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="modal-header">
                                <h4 class="modal-title" id="modal-title">Location</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                
                            <div class="modal-body" id="modal-body-loc">
                            </div>
                
                            <div class="modal-footer" id="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>    
            
            </div>
        </div>  
    </div>  
</form> 

@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script>
    $(document).ready( function () {
        getLocation()
    });

        function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
           
        } else { 
            alert("Geolocation is not supported by this browser.")
        }
        }
        function showPosition(position) {
            var loc = position.coords.latitude+', '+position.coords.longitude
            $.ajax({
                url: "{{ route('attendance.getLocation') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {loc: loc},
                dataType: 'html',
                success: function (response) 
                {
                    $('.loc').val(response)
                }
            });
        }
        $("#check-in").click(function() {
            $("#modal-att").modal("show")
        })
        $('body').on('click', '.show-loc', function (event) {
            event.preventDefault();

            var me = $(this),
                url = me.attr('href'),
                title = me.attr('title');

            $('#modal-title').text(title);
            $('#modal-btn-save').addClass('hide');

            $.ajax({
                url: url,
                dataType: 'html',
                success: function (response) {
                    $('#modal-body-loc').html(response);
                }
            });

            $('#modal-location').modal('show');
        });
        $('[data-mask]').inputmask()
</script>
@endpush