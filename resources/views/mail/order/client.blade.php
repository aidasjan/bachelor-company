<p>{{__('main.hello')}},</p>
<p>{{__('email.thank_you')}}</p>
<p><b>{{__('email.order_details')}}:</b></p>

<table border='1' style='border: 1px solid black; border-collapse: collapse;'>
    <tr>
        <th style='padding: 5px 10px'>{{__('main.code')}}</th>
        <th style='padding: 5px 10px'>{{__('main.name')}}</th>
        <th style='padding: 5px 10px'>{{__('main.qty')}}</th>
        <th style='padding: 5px 10px'>{{__('main.unit')}}</th>
        <th style='padding: 5px 10px'>{{__('main.price')}}</th>
        <th style='padding: 5px 10px'>{{__('main.discount')}}</th>
        <th style='padding: 5px 10px'>{{__('main.final_price')}}*</th>
        <th style='padding: 5px 10px'>{{__('main.total')}}*</th>
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
    <p><b>{{__('main.total')}}*: {{$totalEUR}} EUR</b></p>
@elseif ($totalEUR == 0 && $totalUSD > 0)
    <p><b>{{__('main.total')}}*: {{$totalUSD}} USD</b></p>
@else 
    <p><b>{{__('main.total')}}*: {{$totalEUR}} EUR + {{$totalUSD}} USD</b></p>
@endif

<p>* {{__('email.price_with_discount')}}</p>
<p><b><a href='{{url('/orders'.'/'.$order->id)}}'>{{__('email.open')}}</a></b><p>
<p><b>-----<br>{{config('custom.company_info.name')}}<br>{{config('custom.company_info.email')}}<br>{{config('custom.company_info.phone')}}<br>-----</b></p>
<p><small>{{__('email.sub_message')}}</small><p>