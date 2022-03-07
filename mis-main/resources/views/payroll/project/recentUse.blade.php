@if ($model != null)
<div class="card">
    <div class="card-header">
      <h3 class="card-title"><strong>Recent Use</strong></h3>
      <div class="card-tools">
        <button type="button" class="btn btn-success" id="useIt">Use It </button>
        <input type="hidden" id="projectPayrollId" value="{{ $model->id }}">
      </div>
    </div>
    <div class="card-body">
        <h4>{{ $model->group->name }}</h4>
        @php
            $dateObj   = DateTime::createFromFormat('!m', $model->month);
            $monthName = $dateObj->format('F'); // March
        @endphp 
        <h4>{{ $monthName.' - '.$model->year }}</h4>
      <hr>
      <div class="row">
        <div class="col-md-6">
            <h5>Allowance</h5>
            <hr>
            <div class="row">
                @foreach ($allowance as $item)
                <div class="col-md-3">
                    <label>
                        {{ $item }}
                    </label>                                        
                </div>
                @endforeach                                
            </div>
        </div>
        <div class="col-md-6">
            <h5>Deduction</h5>
            <hr>
            <div class="row">
                @foreach ($deduction as $item)
                <div class="col-md-3">
                    <label>
                        {{ $item }}
                    </label>                                        
                </div>
                @endforeach                                
            </div>
        </div>
    </div>
    </div>
  </div>

<script>
    $('#useIt').click(function() {
        Swal.fire({
        title: 'Are you sure want to use it ?',
        // text: '',
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Use It !'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("payroll.project.useIt") }}',
                    type: "POST",
                    data: {
                        '_method': 'POST',
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'id': $('#projectPayrollId').val(),
                        'project': $('#src_job_field :selected').val(),
                        'month': $('#month :selected').val(),
                        'year': $('#year :selected').val(),
                        'cut_off_salary': $('#cut-off-salary').val(),
                    'cut_off_allowance': $('#cut-off-allowance').val(),
                    'payment_date': $('#payment-date').val(),
                    },
                    success: function (response) {

                        if (response.res == 'success') {
                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: 'Data has been Saved !'
                            }); 
                            var url = "{{ url('/payroll/project') }}"+'/'+response.id 
                            window.location.replace(url)                 
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        });
                    }
                });
            }
        });
    })
</script>
@endif