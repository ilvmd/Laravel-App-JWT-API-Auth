<?php


namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait Utils
{
    /**
     * Done
     */
    private function parseResponse($data = [], $errors = [], $status = 200, $error = false)
    {
        return (object)[
            "error" => $error,
            "status" => $status,
            "errors" => $errors,
            "response" => $data,
        ];
    }
    /**
     * Done
     */
    private function validateSingleQueryParam($table, $column)
    {
        $validator = Validator::make(
            [request()->id],
            [0 => 'required|exists:' . $table . ',id'],
            ["0.exists" => "The selected :attribute is not exists."],
            ["0" => $column]
        );

        if ($validator->fails()) {
            return $this->parseResponse(
                [],
                $validator->errors()->getMessages(),
                400,
                true
            );
        }

        return $this->parseResponse([], [], 200, false);
    }
}
