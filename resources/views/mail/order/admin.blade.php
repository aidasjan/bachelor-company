<p>Hello,</p>
<p>New order has been submitted</p>
<p><b>CLIENT: {{$user->name}} ({{$user->email}})</b></p>
<p><b>Order details:</b></p>

<table border='1' style='border: 1px solid black; border-collapse: collapse;'>
    <tr>
        <th style='padding: 5px 10px'>CODE</th>
        <th style='padding: 5px 10px'>NAME</th>
        <th style='padding: 5px 10px'>QTY</th>
        <th style='padding: 5px 10px'>UNIT</th>
        <th style='padding: 5px 10px'>PRICE</th>
        <th style='padding: 5px 10px'>DISCOUNT</th>
        <th style='padding: 5px 10px'>FINAL PRICE*</th>
        <th style='padding: 5px 10px'>TOTAL*</th>
    </tr>
    @foreach ($order->orderProducts as $order_product)
        <tr>
            <td style='padding: 5px 10px'>{{$order_product->code}}</td>
            <td style='padding: 5px 10px'>{{$order_product->name}}</td>
            <td style='padding: 5px 10px'>{{$order_product->quantity}}</td>
            <td style='padding: 5px 10px'>{{$order_product->unit}}</td>
            <td style='padding: 5px 10px'>{{number_format($order_product->price, 2, '.', '').' '.$order_product->currency}}</td>
            <td style='padding: 5px 10px'>{{number_format($order_product->getDiscount($user), 2, '.', '')}}%</td>
            <td style='padding: 5px 10px'>{{number_format($order_product->getPriceWithDiscount($user), 2, '.', '').' '.$order_product->currency}}</td>
            <td style='padding: 5px 10px'>{{number_format($order_product->getTotalPrice($user), 2, '.', '').' '.$order_product->currency}}</td>
        </tr>
    @endforeach
</table>

@if ($totalEUR > 0 && $totalUSD == 0)
    <p><b>TOTAL*: {{$totalEUR}} EUR</b></p>
@elseif ($totalEUR == 0 && $totalUSD > 0)
    <p><b>TOTAL*: {{$totalUSD}} USD</b></p>
@else 
    <p><b>TOTAL*: {{$totalEUR}} EUR + {{$totalUSD}} USD</b></p>
@endif

<p>* Price with discount</p>
<p><b><a href='{{url('/orders'.'/'.$order->id)}}'>OPEN IN WMP</a></b><p>