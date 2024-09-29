<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidateNioRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (env('DB_SYNC', false)) {
            $inquiry_payload = [
                'NIO'       => $value,
                'ReqDate'   => now()->format('Y-m-d'),
            ];
            $inquiry_payload['Signature'] = base64_encode(hash_hmac(
                'sha256',
                $inquiry_payload['NIO'] . '|' . $inquiry_payload['ReqDate'],
                'jatim',
                true
            ));
            $inquery_tad_response = Http::withHeaders(
                [
                    'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
                ]
            )->withoutVerifying()
                ->post(
                    'https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/InquiryTAD',
                    $inquiry_payload
                );
            $inquery_tad_response_json = $inquery_tad_response->json();
            return count($inquery_tad_response_json['ListTAD']) === 1 ? true : false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'sudah ada sebelumnya.';
    }
}
