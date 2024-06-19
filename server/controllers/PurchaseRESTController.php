<?php

require_once('RESTController.php');
require_once('models/Purchase.php');

class PurchaseRESTController extends RESTController
{
    public function handleRequest()
    {
        switch ($this->method) {
            case 'GET':
                $this->handleGETRequest();
                break;
            case 'POST':
                $this->handlePOSTRequest();
                break;
            case 'PUT':
                $this->handlePUTRequest();
                break;
            case 'DELETE':
                $this->handleDELETERequest();
                break;
            default:
                $this->response('Method Not Allowed', 405);
                break;
        }
    }

    /**
     * get single/all purchase or search purchase
     * all purchase: GET api.php?r=purchase
     * single purchase: GET api.php?r=purchase/25 -> args[0] = 25
     * all purchases group by currency: GET api.php?r=purchase/currency/BTC -> verb = currency, args[0] = BTC
     */
    private function handleGETRequest()
    {
        if ($this->verb == null) {
            $purchases = Purchase::getAll();
            $this->response($purchases, 200);
        } elseif (sizeof($this->args) == 1) {
            $purchase = Purchase::getById($this->args[0]);
            if ($purchase) {
                $this->response($purchase, 200);
            } else {
                $this->response("Not Found", 404);
            }
        } elseif ($this->verb == 'currency' && sizeof($this->args) == 1) {
            $purchases = Purchase::getByCurrency($this->args[0]);
            $this->response($purchases, 200);
        } else {
            $this->response("Bad Request", 400);
        }
    }

    /**
     * create purchase: POST api.php?r=purchase
     */
    private function handlePOSTRequest()
    {
        $model = new Purchase();
        $model->setDate($this->getDataOrNull('date'));
        $model->setAmount($this->getDataOrNull('amount'));
        $model->setPrice($this->getDataOrNull('price'));
        $model->setCurrency($this->getDataOrNull('currency'));

        if ($model->save()) {
            $this->response("OK", 201);
        } else {
            $this->response($model->getErrors(), 400);
        }
    }

    /**
     * update purchase: PUT api.php?r=purchase/25 -> args[0] = 25
     */
    private function handlePUTRequest()
    {
        // TODO
    }

    /**
     * delete purchase: DELETE api.php?r=purchase/25 -> args[0] = 25
     */
    private function handleDELETERequest()
    {
        if ($this->verb == null && sizeof($this->args) == 1) {
            Purchase::delete($this->args[0]);
            $this->response("OK", 200);
        } else {
            $this->response("Not Found", 404);
        }
    }

}
