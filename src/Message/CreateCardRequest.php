<?php

namespace Omnipay\Datatrans\Message;

use Omnipay\Datatrans\Gateway;

class CreateCardRequest extends AuthorizeRequest // FIXME: skip the authorize
{
    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign');

        // The amount must be zero.

        $this->setAmount(0.0);

        // Tell the authorization we want to create a card reference.

        $this->setCreateCard(true);

        return parent::getData();
    }
}
