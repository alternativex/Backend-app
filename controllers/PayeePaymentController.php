<?php

class PayeePaymentController extends ApiController
{
    public function unpaidPayeePayments()
    {
        $query = PayeePayment::unpaid();
        $query->with(["payee", "client"]);

        $query->join(User::table()." as user", 'user.code', '=', PayeePayment::table().'.payee_code');

        $filters = Request::get('_filter');
        if (count($filters))
            foreach ($filters as $key => $filter) {
                list($field, $value) = explode(':', $filter);
                if (strpos($filter, 'search') !== false) {
                    $query->where(function ($query) use ($value) {
                        $query->orWhere("user.name", "like", '%'.$value.'%');
                    });
                } else
                    $this->attachWhere($query, $value, $field);
            }

        $this->attachSort(new PayeePayment(), $query);
        $count = $this->getQueryCount($query);
        $offset = $this->attachOffset($query);
        $limit = $this->attachLimit($query);

        $items = $query->get([PayeePayment::table().'.*']);

        return Response::json(array(
            'model'  => "PayeePayment",
            'items'  => $items->toApiArray(),
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $count
        ), 200, [], JSON_NUMERIC_CHECK);
    }
} 