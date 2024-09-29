<?php

namespace App\Entities\Traits;

trait ResponseTrait
{
    public function responseSuccess($params = [])
    {
        $default = [
            'code'    => 200,
            'status'  => true,
            'message' => 'Success',
        ];

        if (is_string($params)) {
            $default['message'] = $params;
            $params = [];
        }

        $data = array_merge($default, $params);
        return response()->json($data);
    }

    public function responseError($params = [])
    {
        $default = [
            'code'    => 500,
            'status'  => false,
            'message' => 'Error',
        ];

        if (is_string($params)) {
            $default['message'] = $params;
            $params = [];
        }

        $data = array_merge($default, $params);
        return response()->json($data, $data['code']);
    }

    // Select2
    public function responseSelect2($items, $text, $id = 'id')
    {
        $results = [];
        $more = false;
        foreach ($items as $item) {
            $results[] = ['id' => $item->$id, 'text' => $item->$text];
        }
        if (method_exists($items, 'hasMorePages')) {
            $more = $items->hasMorePages();
        }
        return response()->json(compact('results', 'more'));
    }

    // Transaction
    public function beginTransaction()
    {
        \DB::beginTransaction();
    }

    public function commit($params = [])
    {
        \DB::commit();
        return $this->responseSuccess($params);
    }

    public function rollback($params = [])
    {
        \DB::rollback();
        return $this->responseError($params);
    }

    // Saved
    public function commitSaved($params = [])
    {
        \DB::commit();
        $message = __('base.success.saved');
        return $this->responseSuccess(array_merge(compact('message'), $params));
    }

    public function rollbackSaved($e, $params = [])
    {
        \DB::rollback();
        $message = __('base.error.saved');
        $errors = $e->getMessage();
        return $this->responseError(array_merge(compact('message', 'errors'), $params));
    }

    // Deleted
    public function commitDeleted($params = [])
    {
        \DB::commit();
        $message = __('base.success.deleted');
        return $this->responseSuccess(array_merge(compact('message'), $params));
    }

    public function rollbackDeleted($e, $params = [])
    {
        \DB::rollback();
        $message = __('base.error.saved');
        $errors = $e->getMessage();
        return $this->responseError(array_merge(compact('message', 'errors'), $params));
    }
}
