<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(Request $request) {
        $defaultInfo = [
            'BUILD_NUMBER' => 'N/A',
            'BUILD_ID' => 'N/A',
            'JOB_NAME' => 'N/A',
            'BUILD_URL' => 'N/A',
            'GIT_COMMIT' => 'N/A',
            'GIT_BRANCH' => 'N/A',
            'BUILD_DATE' => 'N/A',
        ];

        try {
            $jsonString = @file_get_contents(base_path('build_info.json'));

            if ($jsonString === false) {
                throw new \ErrorException('Error reading build info');
            }

            $buildInfo = json_decode($jsonString, true);

            if ($buildInfo === null && json_last_error() != JSON_ERROR_NONE) {
                throw new \ErrorException('Error decoding the JSON data: ' . json_last_error_msg());
            }
        } catch (\ErrorException $e) {
            $buildInfo = $defaultInfo;
            $buildInfo['Error'] = $e->getMessage();
        }

        return Inertia::render('about', [
            'build_info' => $buildInfo,
        ]);
    }
}
