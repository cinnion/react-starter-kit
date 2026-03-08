<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AboutControllerTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        if (file_exists('build_info.json')) {
            unlink('build_info.json');
        }
    }

    public function testAboutIndexNoBuildInfoFileReturnsNAWithError()
    {
        // Arrange to make sure build_info.json file does not exist.
        if (file_exists('build_info.json')) {
            unlink('build_info.json');
        }
        $this->assertFileDoesNotExist('build_info.json');

        // Act
        $response = $this->get(route('about'));

        // Assert
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('about')
                ->has('build_info', fn (Assert $page) => $page
                    ->where('BUILD_ID', 'N/A')
                    ->where('BUILD_NUMBER', 'N/A')
                    ->where('JOB_NAME', 'N/A')
                    ->where('BUILD_URL', 'N/A')
                    ->where('GIT_COMMIT', 'N/A')
                    ->where('GIT_BRANCH', 'N/A')
                    ->where('BUILD_DATE', 'N/A')
                    ->where('Error', 'Error reading build info')
                )
            );
    }

    public function testAboutIndexBadBuildInfoFileReturnsNAWithError()
    {
        // Arrange to make sure build_info.json file does not exist.
        $data = 'Some junk';
        file_put_contents('build_info.json', $data);
        $this->assertFileExists('build_info.json');

        // Act
        $response = $this->get(route('about'));

        // Assert
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('about')
                ->has('build_info', fn (Assert $page) => $page
                    ->where('BUILD_ID', 'N/A')
                    ->where('BUILD_NUMBER', 'N/A')
                    ->where('JOB_NAME', 'N/A')
                    ->where('BUILD_URL', 'N/A')
                    ->where('GIT_COMMIT', 'N/A')
                    ->where('GIT_BRANCH', 'N/A')
                    ->where('BUILD_DATE', 'N/A')
                    ->where('Error', 'Error decoding the JSON data: Syntax error')
                )
            );
    }

    public function testAboutIndexGoodBuildInfoFileReturnsInfoWithoutError()
    {
        // Arrange to make sure build_info.json file does not exist.
        $data = [
            'BUILD_ID' => 'Build ID',
            'BUILD_NUMBER' => 'Build Number',
            'JOB_NAME' => 'Job Name',
            'BUILD_URL' => 'Build URL',
            'GIT_COMMIT' => 'Git Commit',
            'GIT_BRANCH' => 'Git Branch',
            'BUILD_DATE' => 'Build Date',
        ];
        $jsonData = json_encode($data);
        file_put_contents('build_info.json', $jsonData);
        $this->assertFileExists('build_info.json');

        // Act
        $response = $this->get(route('about'));

        // Assert
        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('about')
                ->has('build_info', fn (Assert $page) => $page
                    ->where('BUILD_ID', 'Build ID')
                    ->where('BUILD_NUMBER', 'Build Number')
                    ->where('JOB_NAME', 'Job Name')
                    ->where('BUILD_URL', 'Build URL')
                    ->where('GIT_COMMIT', 'Git Commit')
                    ->where('GIT_BRANCH', 'Git Branch')
                    ->where('BUILD_DATE', 'Build Date')
                    ->missing('Error')
                )
            );
    }
}
