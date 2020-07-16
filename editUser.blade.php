@extends('admin.layouts.master')
@section("title") Edit User - Dashboard
@endsection
@section('content')
<style>
    #showPassword {
    cursor: pointer;
    padding: 5px;
    border: 1px solid #E0E0E0;
    border-radius: 0.275rem;
    color: #9E9E9E;
    }
    #showPassword:hover {
    color: #616161;
    }
</style>
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-circle-right2 mr-2"></i>
                <span class="font-weight-bold mr-2">Editing</span>
                <span class="badge badge-primary badge-pill animated flipInX">"{{ $user->email }}"</span>
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.updateUser') }}" method="POST" enctype="multipart/form-data">
                        <legend class="font-weight-semibold text-uppercase font-size-sm">
                            <i class="icon-address-book mr-2"></i> User Details
                        </legend>
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Name:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control form-control-lg" name="name"
                                    value="{{ $user->name }}" placeholder="Enter Full Name" required
                                    autocomplete="new-name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Email:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control form-control-lg" name="email"
                                    value="{{ $user->email }}" placeholder="Emter Email Address" required
                                    autocomplete="new-email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Phone:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control form-control-lg" name="phone" value="{{ $user->phone }}" 
                                    placeholder="Enter Phone Number" required autocomplete="new-phone">
                            </div>
                        </div>
                        <div class="form-group row form-group-feedback form-group-feedback-right">
                            <label class="col-lg-3 col-form-label">Password:</label>
                            <div class="col-lg-9">
                                <input id="passwordInput" type="password" class="form-control form-control-lg"
                                    name="password" placeholder="Enter Password (min 6 characters)"
                                    autocomplete="new-password">
                            </div>
                            <div class="form-control-feedback form-control-feedback-lg">
                                <span id="showPassword"><i class="icon-unlocked2"></i> Show</span>
                            </div>
                        </div>
                        <legend class="font-weight-semibold text-uppercase font-size-sm">
                            <i class="icon-tree7 mr-2"></i> Role Management
                        </legend>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Current Role:</label>
                            <div class="col-lg-9">
                                @foreach ($user->roles as $role)
                                <span class="badge badge-flat border-grey-800 text-default text-capitalize font-size-lg">
                                {{ $role->name }}
                                </span> @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">New Role:</label>
                            <div class="col-lg-9">
                                <select multiple="multiple" class="form-control select" data-fouc name="roles[]">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" class="text-capitalize">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($user->hasRole("Delivery Guy"))
                            <legend class="font-weight-semibold text-uppercase font-size-sm">
                                <i class="icon-truck mr-2"></i> Delivery Guy Details
                            </legend>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Name or Nick Name:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control form-control-lg" name="delivery_name"
                                        value="{{ !empty($user->delivery_guy_detail->name) ? $user->delivery_guy_detail->name : "" }}" placeholder="Enter Name or Nickname of Delivery Guy" required
                                        autocomplete="new-name">
                                        <span class="help-text text-muted">This name will be displayed to the user/customers</span>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Age</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control form-control-lg" name="delivery_age"
                                        value="{{ !empty($user->delivery_guy_detail->age) ? $user->delivery_guy_detail->age : "" }}" placeholder="Enter Delivery Guy's Age">
                                </div>
                            </div>
                            <div class="form-group row">
                                @if(!empty($user->delivery_guy_detail->photo))
                                <div class="col-lg-9 offset-lg-3">
                                    <img src="{{ substr(url('/'), 0, strrpos(url('/'), '/')) }}/assets/img/delivery/{{ $user->delivery_guy_detail->photo }}" alt="delivery-photo" class="img-fluid mb-2" style="width: 90px; border-radius: 50%">
                                </div>
                                @endif
                                <label class="col-lg-3 col-form-label">Delivery Guy's Photo:</label>
                                <div class="col-lg-9">
                                    <input type="file" class="form-control-uniform" name="delivery_photo" data-fouc>
                                    <span class="help-text text-muted">Image size 250x250</span>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Description</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control form-control-lg" name="delivery_description"
                                        value="{{ !empty($user->delivery_guy_detail->description) ? $user->delivery_guy_detail->description : "" }}" placeholder="Enter Short Description about this Delivery Guy">
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Vehicle Number</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control form-control-lg" name="delivery_vehicle_number"
                                        value="{{ !empty($user->delivery_guy_detail->vehicle_number) ? $user->delivery_guy_detail->vehicle_number : "" }}" placeholder="Enter Delivery Guy's Vehicle Number">
                                </div>
                            </div>
                            @if(config('settings.enableDeliveryGuyEarning') == 'true')
                            <hr>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Commission Rate %</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control form-control-lg commission_rate" name="delivery_commission_rate" placeholder="Commission Rate %" value="{{  !empty($user->delivery_guy_detail->commission_rate) ? $user->delivery_guy_detail->commission_rate : "" }}" required="required">
                                </div>
                            </div>
                            @endif
                        @endif
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                            UPDATE
                            <i class="icon-database-insert ml-1"></i>
                            </button>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <legend class="font-weight-semibold text-uppercase font-size-sm">
                            <i class="icon-piggy-bank mr-2"></i> Current {{ config("settings.walletName") }} Balance:  <span style="font-size: 1rem;">{{ config('settings.currencyFormat') }} {{ $user->balanceFloat }}</span>
                            <button class="btn btn-md btn-default" id="viewTransactions" style="float: right; border: 1px solid #ccc; line-height: 10px;">View Transactions</button>
                    </legend>
                    <button class="btn btn-primary btn-labeled btn-labeled-left mr-2" id="addAmountButton"><b><i class="icon-plus2"></i></b> Add Amount</button>
                    <button class="btn btn-secondary btn-labeled btn-labeled-left mr-2" id="substractAmountButton"><b><i class="icon-minus3"></i></b> Substract Amount</button>

                    <form action="{{ route('admin.addMoneyToWallet') }}" method="POST" id="addAmountForm" class="hidden" style="margin-top: 2rem;">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="text-danger">*</span>Add Money:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control form-control-lg balance" name="add_amount"
                                    placeholder="Amount in {{ config('settings.currencyFormat') }}" required>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="text-danger">*</span>Message:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control form-control-lg" name="add_amount_description"
                                    placeholder="Short Description or Message" required>
                            </div>
                        </div>
                        @csrf
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                            Update Balance
                            <i class="icon-database-insert ml-1"></i>
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('admin.substractMoneyFromWallet') }}" method="POST" id="substractAmountForm" class="hidden" style="margin-top: 2rem;">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="text-danger">*</span>Substract Money:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control form-control-lg balance" name="substract_amount"
                                    placeholder="Amount in {{ config('settings.currencyFormat') }}" required>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="text-danger">*</span>Message:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control form-control-lg" name="substract_amount_description"
                                    placeholder="Short Description or Message" required>
                            </div>
                        </div>
                        @csrf
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                            Update Balance
                            <i class="icon-database-insert ml-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="tansactionsDiv">
         <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                     <legend class="font-weight-semibold text-uppercase font-size-sm">
                         
                            <i class="icon-transmission mr-2"></i> {{ config('settings.walletName') }} Transactions
                            <button  onclick="exceller()" class='btn btn-danger'>Exportar relat贸rio</button>
                            
                    </legend>
                    @if(count($user->transactions) > 0)
                    <div class="table-responsive">
                        <table id="myTable" class="w3-table-all">
                            
                            
                            <thead>
                                <tr>
                                    <th>
                                        Type
                                    </th>
                                    <th width="20%">
                                        Amount
                                    </th>
                                    <th>
                                       
                                        
                                        Description
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->transactions->reverse() as $transaction)
                                <tr>
                                    <td class="item">
                                        @if($transaction->type === "deposit")
                                            <span class="badge badge-flat border-grey-800 text-success text-capitalize">{{$transaction->type}}</span>
                                        @else
                                            <span class="badge badge-flat border-grey-800 text-danger text-capitalize">{{$transaction->type}}</span>
                                        @endif
                                    </td class="item">
                                    <td class="item">
                                        {{ config('settings.currencyFormat') }} {{ number_format($transaction->amount / 100) }}
                                    </td>
                                    <td class="item">
                                       <?php  $sub = substr($transaction->meta["description"],22);   ?>
                                    
                                    <span class="badge badge-flat border-grey-800 text-default text-capitalize text-left">
                                        <a href="https://appchegou.com/public/admin/order/<?php echo $sub ?>">{{$transaction->meta["description"] }}</a>
                                    
                                        
                                    </td>
                                    <td class="item">
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">No transactions have been made from {{ config('settings.walletName') }}</p>
                    @endif   
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.form-control-uniform').uniform();

        $("#showPassword").click(function (e) { 
            $("#passwordInput").attr("type", "text");
        });
        $('.select').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Select Role/s (Old roles will be revoked and these roles will be applied)',
        });
        $('.balance').numeric({allowThouSep:false, maxDecimalPlaces: 2 });

        $("#addAmountButton").click(function(event) {
            $('#addAmountButton').hide();
            $('#substractAmountButton').hide();
            $("#addAmountForm").removeClass('hidden');
            $("#substractAmountForm").addClass('hidden');
        });

        $("#substractAmountButton").click(function(event) {
            $('#addAmountButton').hide();
            $('#substractAmountButton').hide();
            $("#addAmountForm").addClass('hidden');
            $("#substractAmountForm").removeClass('hidden');
        });

        $("#viewTransactions").click(function(event) {
            var targetOffset = $('#tansactionsDiv').offset().top - 70;
            $('html, body').animate({scrollTop: targetOffset}, 500);
        });

        $('.commission_rate').numeric({ allowThouSep:false, maxDecimalPlaces: 2, max: 100, allowMinus: false });
    });
</script>


<script>
  function exceller() {
    var uri = 'data:application/vnd.ms-excel;base64,',
      template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="gb18030"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
      base64 = function(s) {
        return window.btoa(unescape(encodeURIComponent(s)))
      },
      format = function(s, c) {
        return s.replace(/{(\w+)}/g, function(m, p) {
          return c[p];
        })
      }
    var myTable = document.getElementById("myTable").innerHTML;
    var ctx = {
      worksheet: name || '',
      table: myTable
    };
    var link = document.createElement("a");
    link.download = "export.xls";
    link.href = uri + base64(format(template, ctx))
    link.click();
  }
</script>

<script>


function sortTable(table, dir, n) {
  var rows, switching, i, x, y, shouldSwitch, switchcount = 0;
  switching = true;
  /*Faça um loop que continuará até
   nenhuma troca foi feita:*/
  while (switching) {
    //comece dizendo: nenhuma troca é feita:
    switching = false;
    rows = table.rows;
    /*Faça um loop por todas as linhas da tabela (exceto o
     primeiro, que contém cabeçalhos da tabela):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //comece dizendo que não deve haver alternância:
      shouldSwitch = false;
      /*Obtenha os dois elementos que você deseja comparar,
       um da linha atual e o outro da próxima:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*verifique se as duas linhas devem mudar de lugar,
       com base na direção, asc ou desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //Nesse caso, marque como uma opção e interrompa o loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //Nesse caso, marque como uma opção e interrompa o loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*Se um interruptor foi marcado, faça-o
       e marque que uma troca foi feita:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Cada vez que uma troca for concluída, aumente essa contagem em 1:
      switchcount++;
    } else {
      /*Se nenhuma mudança foi feita E a direção for "asc",
       defina a direção para "desc" e execute o loop while novamente.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

var myTable = document.getElementById('myTable');
sortTable(myTable, 'asc', 0);

//Button click:
let ord = false;
document.getElementById("button1").onclick = function() {
  if (!ord) {
    sortTable(myTable, 'asc', 0);
    //sortTable(myTable, 'asc', 1);
    ord = true;
  } else {
    sortTable(myTable, 'desc', 0);
    //sortTable(myTable, 'desc', 1);
    ord = false;
  }
}</script>

@endsection