<hr>
<h4>
    Set Permissions - {{ $emp[0]->name }}
</h4>
<form method='POST' id="myForm" action="{{ route('er.project.permission.empUpdate') }}">
    <div class="row">
        <input type="hidden" value="{{ $emp[0]->uuid }}" name="uuid"/>
        @foreach ($permissions as $permission)
        @php
            $check = '';                                            
        @endphp
        @if (in_array($permission->id,$PermissionEmployee))
            @php
                $check = 'checked';                                            
            @endphp
        @endif
            <div class="col-md-6">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="permission[]" id="permission{!! $permission->id !!}" value="{!! $permission->id !!}" {!! $check !!}>
                    <label for="permission{!! $permission->id !!}" class="custom-control-label">{!! $permission->display_name !!}</label>
                </div>     
            </div>
        @endforeach
    </div>
    <br/>
    <button type="button" id="submit" class="btn btn-primary">Submit</button>
</form>

<script>
$('#submit').click(function() {

    var data = $('#myForm').serialize()
    $.ajax({
            url : '{{ route("er.project.permission.empUpdate") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'html',
            data: data,
            success: (data) => {
                emp()
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
})
</script>