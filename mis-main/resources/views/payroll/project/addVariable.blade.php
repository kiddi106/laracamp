<form action="{{ route('payroll.project.storeAddVariable') }}" method="post">
    @csrf
    <input type="hidden" name="projectPayrollId" value="{{ $projectPayrollId }}">
    <div class="row">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td style="width: 50%">
                <h4>Allowance</h4>
                <hr>
                <div class="row">
                    @foreach (App\Models\Payroll\Variables::where('type',1)->get() as $item)
                    <div class="col-md-6">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="{{ $item->id }}" class="variables" value="{{ $item->id }}" name="variables[]">
                            <label for="{{ $item->id }}">
                                {{ $item->name }}
                            </label>
                        </div>  
                    </div>
                    @endforeach                                
                </div>
              </td>
              <td style="width: 50%">
                <h4>Deduction</h4>
                <hr>
                <div class="row">
                    @foreach (App\Models\Payroll\Variables::where('type',2)->get() as $item)
                    <div class="col-md-6">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="{{ $item->id }}" class="variables" value="{{ $item->id }}" name="variables[]">
                            <label for="{{ $item->id }}">
                                {{ $item->name }}
                            </label>
                        </div>  
                    </div>
                    @endforeach                                
                </div>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
</form>