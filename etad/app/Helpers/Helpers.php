<?php

use Modules\Master\Entities\SO\OrgStruct;

if (!function_exists('str_to_array')) {
    function str_to_array($string, $constraint = '|', $delimiter = ':')
    {
        if (is_string($string)) {
            $values = explode($constraint, $string);
            $string = [];
            foreach ($values as $item) {
                $col = explode($delimiter, $item);
                $key = trim($col[0]);
                $val = trim($col[1]);
                switch ($val) {
                    case 'true':
                        $string[$key] = true;
                        break;
                    case 'false':
                        $string[$key] = false;
                        break;
                    case 'null':
                        $string[$key] = null;
                        break;
                    default:
                        $string[$key] = $val;
                        break;
                }
            }
        }
        return $string;
    }
}

if (!function_exists('read_more_raw')) {
    function read_more_raw($text, $maxLength = 150)
    {
        $return = $text;
        if (strlen($text) > $maxLength) {
            $return   = substr($text, 0, $maxLength);
            $readmore = substr($text, $maxLength);

            $return .= '<a href="javascript: void(0)" class="read-more" style="cursor:pointer;" onclick="$(this).parent().find(\'.read-more-cage\').show(); $(this).hide()"> Read more...</a>';

            $readless = '<a href="javascript: void(0)" class="read-less" style="cursor:pointer;" onclick="$(this).parent().parent().find(\'.read-more\').show(); $(this).parent().hide()"> Read less...</a>';

            $return = "<span>{$return}<span style='display: none' class='read-more-cage'>{$readmore} {$readless}</span></span>";
        }

        return $return;
    }
}

if (!function_exists('read_more')) {
    function read_more($text, $maxLength = 150)
    {
        return utf8_decode(read_more_raw($text, $maxLength));
    }
}

if (!function_exists('label_status')) {
    function label_status($status)
    {
        switch ($status) {
            case 'draft':
                return '<span class="label label-warning label-inline text-nowrap">DRAFT</span>';
            case 'rejected':
                return '<span class="label label-danger label-inline text-nowrap">REJECTED</span>';
            case 'waiting.approval':
                return '<span class="label label-primary label-inline text-nowrap">WAITING APPROVAL</span>';
            case 'waiting.upload':
                return '<span class="label label-primary label-inline text-nowrap">WAITING UPLOAD</span>';
            case 'completed':
                return '<span class="label label-success label-inline text-nowrap">COMPLETED</span>';
            default:
                return '<span class="label label-info label-inline text-nowrap">NEW</span>';
        }
    }
}

if (!function_exists('getQrcode')) {
    function getQrcode($data)
    {
        $code = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->merge(config('base.logo.barcode'), 0.4, true)
            ->size(180)
            ->errorCorrection('H')
            ->margin(0)
            ->generate($data, 0, 10);
        $result = '<div class="visible-print text-center m-0">
                            <img style="height:100px;" src="data:image/png;base64,' . base64_encode($code) . '">
                        </div>';
        return $result;
    }
}

if (! function_exists('getRoot')) {
    function getRoot()
    {
        return OrgStruct::with('city')
            ->where('level', 'root')
            ->first();
    }
}

if (! function_exists('getCompanyCity')) {
    function getCompanyCity()
    {
        return getRoot()->city ?? null;
    }
}

if (! function_exists('getCompanyCityname')) {
    function getCompanyCityname()
    {
        return getRoot()->city->name ?? '';
    }
}
