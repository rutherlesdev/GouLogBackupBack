@extends('admin.layouts.master')
@section("title") Orders - Dashboard
@endsection
@section('content')zz
<style>
.pulse {
    display: inline-block;
    width: 12.5px;
    height: 12.5px;
    border-radius: 50%;
    animation: pulse 1.2s infinite;
    vertical-align: middle;
    margin: -3px 0 0 3px;
}
.pulse-warning {
    background: #ffc107;
}
.pulse-danger {
    background: #ff5722;
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(255,87,34, 0.5);
  }
  50% {
      box-shadow: 0 0 0 26px rgba(255,87,34, 0);
  }
  100% {
      box-shadow: 0 0 0 0 rgba(255,87,34, 0);
  }
}
</style>
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-circle-right2 mr-2"></i>
                <span class="font-weight-bold mr-2">TOTAL</span>
                <span class="badge badge-primary badge-pill animated flipInX">{{ $count }}</span>
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
<div class="content">
    
   
    
    <form action="{{ route('admin.post.searchOrders') }}" method="GET">
        <div class="form-group form-group-feedback form-group-feedback-right search-box">
            
           <p> <input id="myInput" onkeyup="myFunction()" type="text" class="form-control form-control-lg search-input"
                placeholder="filtre pelo Nº do Pedido " name="query"></p>
                
                <p><input id="myInput2" onkeyup="myFunction2()" type="text" class="form-control form-control-lg search-input"
                placeholder="Pesquise pelo nome da empresa..." name="query"></p>
                
             
                 
                    <p><input id="myInput3" onkeyup="myFunction3()" type="text" class="form-control form-control-lg search-input"
                placeholder="Pesquise pelo nome do entregador..." name="query"></p>
                
            
            <div class="form-control-feedback form-control-feedback-lg">
                <i class="icon-search4"></i>
            </div>
        </div>
        @csrf
    </form>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                
                <button  onclick="exceller()" class='btn btn-danger'>Exportar relatório</button>
                
                
                <table id="myTable" class="table">
                    <thead>
                        <tr>
                            <th>Pedido ID</th>
                            <th>Data e hora</th>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Pagamento</th>
                            <th>Hora</th>
                            <th class="text-center" style="width: 10%;"><i class="
                                icon-circle-down2"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>
                                {{ $order->unique_order_id }}
                                @if(config("settings.restaurantAcceptTimeThreshold") != NULL)
                                    @if ($order->orderstatus_id == 1)
                                        @if($order->created_at->diffInMinutes(\Carbon\Carbon::now()) >= (int) config("settings.restaurantAcceptTimeThreshold"))
                                            <span class="pulse pulse-warning" data-popup="tooltip" title="" data-placement="bottom" data-original-title="Order not accepted by restaurant. Late by {{ $order->created_at->diffInMinutes(\Carbon\Carbon::now()) - 5 }} mins."></span>
                                        @endif
                                    @endif
                                @endif
                                @if(config("settings.deliveryAcceptTimeThreshold") != NULL)
                                    @if ($order->orderstatus_id == 2)
                                       @if($order->created_at->diffInMinutes(\Carbon\Carbon::now()) >= (int) config("settings.deliveryAcceptTimeThreshold"))
                                            <span class="pulse pulse-danger" data-popup="tooltip" title="" data-placement="bottom" data-original-title="Order not on accepted by delivery guy. Late by {{ $order->created_at->diffInMinutes(\Carbon\Carbon::now()) - 5 }} mins."></span>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            
                            <td> {{ $order->created_at}}  </td>
                            
                            
                            
                            
                            <td>{{ $order->user->name }}</td>
                            <td>
                                <span class="badge badge-flat border-grey-800 text-default text-capitalize text-left">
                                    @if ($order->orderstatus_id == 1) Pedido Feito @endif
                                    @if ($order->orderstatus_id == 2) Pedido Aceito @endif
                                    @if ($order->orderstatus_id == 3) <b style="color:red"> Entrega Iniciada 🚨 </b> @endif
                                    @if ($order->orderstatus_id == 4)<b style="color:red"> Entrega a caminho 🚨 </b>  @endif
                                    @if ($order->orderstatus_id == 5) Completado @endif
                                    @if ($order->orderstatus_id == 6) Cancelado @endif
                                    @if ($order->orderstatus_id == 7) Ready to Pickup @endif
                                    
                                    @if($order->orderstatus_id > 2 && $order->orderstatus_id  < 6)
                                    
                                    @if($order->accept_delivery !== null)
                                    @if($order->orderstatus_id > 2 && $order->orderstatus_id  < 6)
                                    
                                    <a href="{{ route('admin.get.editUser', $order->accept_delivery->user->id) }}"
                                    Pelo entregador: <b>{{ $order->accept_delivery->user->name }}</b>
                                    @endif
                                    @endif
                                    @endif
                                </span>
                            </td>
                            <td>{{ config('settings.currencyFormat') }} {{ $order->total }}</td>
                            <td>
                                {{ $order->payment_mode }}
                            </td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.viewOrder', $order->unique_order_id) }}"
                                    class="badge badge-primary badge-icon"> Ver <i
                                    class="icon-file-eye ml-1"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // reload window every 10 mins to refresh order status...
        setTimeout(function() {
            window.location.reload(1);
        }, 10 * 60 * 1000);
    });
</script>


<script>

//pesquisa formulario

function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<script>

//pesquisa formulario

function myFunction2() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput2");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<script>

//pesquisa formulario

function myFunction3() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput3");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
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




@endsection