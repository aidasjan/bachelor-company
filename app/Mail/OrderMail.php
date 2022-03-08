<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function build()
    {
        $this->data['totalEUR'] = number_format($this->data['order']->getTotalOrderPrice($this->data['user'], 'EUR'), 2, '.', ' ');
        $this->data['totalUSD'] = number_format($this->data['order']->getTotalOrderPrice($this->data['order'], 'USD'), 2, '.', ' ');
        if ($this->type == 'admin'){
            return $this->subject('WMP | ORDER '.$this->data['order']->id.' - '.$this->data['user']->name)->view('mail.order.admin')->with($this->data);
        } else {
            return $this->subject('WMP | '.__('main.order').' '.$this->data['order']->id)->view('mail.order.client')->with($this->data);
        }
    }
}
