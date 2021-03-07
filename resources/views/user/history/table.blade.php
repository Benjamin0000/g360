@php
    use App\Http\Helpers;
    $cur = Helpers::LOCAL_CURR_SYMBOL;
@endphp
<table class="table no-wrap table-striped table-hover datatable">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Amount</th>
            <th>Transaction type</th>
            <th>Remark</th>
            <th>Transaction time</th>
        </tr>
    </thead>
    <tbody>
       @if($histories->count())
         @php $count = Helpers::tableNumber($total) @endphp
         @foreach($histories as $history)
           <tr>
             <td>{{$count++}}</td>
             <td>{{$cur.number_format($history->amount, 2, '.', ',')}}</td>
             <td>
               @if($history->type == 'debit')
                   <span class="label label-light-danger">Debit</span>
               @elseif($history->type == 'credit')
                   <span class="label label-light-success">Credit</span>
               @endif
             </td>
             <td>{{$history->description}}</td>
             <td>{{$history->created_at->isoFormat('lll')}}</td>
           </tr>
         @endforeach
       @endif
    </tbody>
</table>
