<?php

$baseUrl = "https://skillhub-production-37e5.up.railway.app";

$passed = 0;
$failed = 0;

function sendRequest($method, $endpoint, $data = null)
{
    global $baseUrl;

    $url = $baseUrl . $endpoint;

    $options = [
        "http" => [
            "method" => $method,
            "header" => "Content-Type: application/json\r\n",
            "ignore_errors" => true
        ]
    ];

    if ($data !== null) {
        $options["http"]["content"] = json_encode($data);
    }

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return null;
    }

    return json_decode($response, true);
}

function assertTest($condition, $testName)
{
    global $passed, $failed;

    if ($condition) {
        echo "PASSED: " . $testName . PHP_EOL;
        $passed++;
    } else {
        echo "FAILED: " . $testName . PHP_EOL;
        $failed++;
    }
}

echo "Running SkillHub Production API Tests..." . PHP_EOL;
echo "---------------------------------------" . PHP_EOL;

$testEmail = "production_student_" . time() . "@example.com";
$testPassword = "test12345";
$testUserId = null;
$testCourseId = 1;

/*
|--------------------------------------------------------------------------
| Test 1: Production User Registration
|--------------------------------------------------------------------------
*/
$registerResponse = sendRequest("POST", "/users", [
    "name" => "Production Test Student",
    "email" => $testEmail,
    "password" => $testPassword,
    "role" => "student"
]);

assertTest(
    is_array($registerResponse) && isset($registerResponse["id"]),
    "Production registration should create a new student user"
);

if (is_array($registerResponse) && isset($registerResponse["id"])) {
    $testUserId = $registerResponse["id"];
}

/*
|--------------------------------------------------------------------------
| Test 2: Production User Login
|--------------------------------------------------------------------------
*/
$loginResponse = sendRequest("POST", "/login", [
    "email" => $testEmail,
    "password" => $testPassword
]);

assertTest(
    is_array($loginResponse)
    && isset($loginResponse["success"])
    && $loginResponse["success"] === true,
    "Production login should succeed for the created student"
);

if (is_array($loginResponse) && isset($loginResponse["user"]["id"])) {
    $testUserId = $loginResponse["user"]["id"];
}

/*
|--------------------------------------------------------------------------
| Test 3: Production Get Courses
|--------------------------------------------------------------------------
*/
$coursesResponse = sendRequest("GET", "/courses");

assertTest(
    is_array($coursesResponse),
    "Production courses endpoint should return a valid response"
);

if (is_array($coursesResponse) && isset($coursesResponse["data"][0]["id"])) {
    $testCourseId = $coursesResponse["data"][0]["id"];
} elseif (is_array($coursesResponse) && isset($coursesResponse[0]["id"])) {
    $testCourseId = $coursesResponse[0]["id"];
}

/*
|--------------------------------------------------------------------------
| Test 4: Production Course Enrollment
|--------------------------------------------------------------------------
*/
$enrollmentResponse = sendRequest("POST", "/enrollments", [
    "user_id" => $testUserId,
    "course_id" => $testCourseId
]);

assertTest(
    is_array($enrollmentResponse) && isset($enrollmentResponse["message"]),
    "Production student should be able to enroll in a course"
);

/*
|--------------------------------------------------------------------------
| Test 5: Production Add Course Review
|--------------------------------------------------------------------------
*/
$reviewResponse = sendRequest("POST", "/reviews", [
    "user_id" => $testUserId,
    "course_id" => $testCourseId,
    "rating" => 5,
    "comment" => "This is an automated production test review."
]);

assertTest(
    is_array($reviewResponse)
    && (isset($reviewResponse["message"]) || isset($reviewResponse["success"])),
    "Production student should be able to leave a course review"
);

/*
|--------------------------------------------------------------------------
| Test 6: Production Get User Notifications
|--------------------------------------------------------------------------
*/
$notificationResponse = sendRequest("GET", "/notifications/user/" . $testUserId);

assertTest(
    is_array($notificationResponse),
    "Production notifications endpoint should return a valid response"
);

echo "---------------------------------------" . PHP_EOL;
echo "Tests passed: " . $passed . PHP_EOL;
echo "Tests failed: " . $failed . PHP_EOL;
echo "Testing finished." . PHP_EOL;