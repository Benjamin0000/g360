
<div style="width:7.938cm;min-height:100px;margin:0 !important;padding:0 !important;">
    <style>
        td{
            font-size:13px;
            font-weight:bolder;
            padding-top: 10px; padding-bottom:10px;
            word-break: break-all;
        }
    </style>
    <div align="center">
        <img src="/assets/images/logo.png" width="200">
    </div>
    <hr>
    <table>
        <tr>
            <td><b>SERVICE</b></td>
            <td>{{$data->operator_name}}</td>
        </tr>
        <tr>
            <td><b>ACCOUNT NO.</b></td>
            <td>{{$data->meter_no}}</td>
        </tr>
        <tr>
            <td><b>NAME</b></td>
            <td>{{$data->name}}</td>
        </tr>
        <tr>
            <td><b>ADDRESS</b></td>
            <td>{{$data->address}}</td>
        </tr>
        <tr>
            <td><b>AMOUNT</b></td>
            <td>₦{{number_format($data->amount)}}</td>
        </tr>
        <tr>
            <td><b>TOKEN</b></td>
            <td>{{$data->pin_code}}</td>
        </tr>
        <tr>
            <td><b>STATUS</b></td>
            <td>Successful</td>
        </tr>
        <tr>
            <td><b>DATE</b></td>
            <td>{{$data->created_at->isoFormat('lll')}}</td>
        </tr>
    </table>
</div>

