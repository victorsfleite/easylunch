@component('mail::message')
# Oi {{ $user->name }}!

Não esqueça de pagar seus almoços para a conta bancária do Admin. Segue abaixo sua fatura e opções de bancos.

@component('mail::table')
| Pedidos                   | Data                                          | Valor                  |
| ------------------------- |:---------------------------------------------:| ----------------------:|
@foreach ($orders as $order)
| {{ $order->description }} | {{ $order->created_at->format('l d/m/Y') }} | R$ {{ $order->price }} |
@endforeach
| **Total**                 |                                               | **R$ {{ $total }}**    |
@endcomponent

Essas são as opções de bancos para transferência do valor:

## Nu Bank

Banco **260 - Nu Pagamentos S.A.**<br>
Agência **0001**<br>
Conta: **691001-3**<br>
CPF: **029.332.271-69**<br>

## Banco Itaú

Banco **Itaú**<br>
Agência **5079**<br>
Conta: **70587-6**<br>
CPF: **029.332.271-69**<br>

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
