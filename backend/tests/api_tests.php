<?php

$baseUrl = "http://localhost:8000";

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
    $response = file_get_contents($url, false, $context);

    return json_decode($response, true);
}

function assertTest($condition, $testName)
{
    if ($condition) {
        echo "PASSED: " . $testName . PHP_EOL;
    } else {
        echo "FAILED: " . $testName . PHP_EOL;
    }
}

echo "Running SkillHub API Tests..." . PHP_EOL;
echo "-----------------------------" . PHP_EOL;

$testEmail = "testuser_" . time() . "@example.com";
$testPassword = "test12345";
$testUserId = null;
$testCourseId = 1;

/*
|--------------------------------------------------------------------------
| Test 1: User Registration
|--------------------------------------------------------------------------
*/
$registerResponse = sendRequest("POST", "/users", [
    "name" => "Test User",
    "email" => $testEmail,
    "password" => $testPassword,
    "role" => "student"
]);

assertTest(
    isset($registerResponse["id"]),
    "User registration should create a new user"
);

if (isset($registerResponse["id"])) {
    $testUserId = $registerResponse["id"];
}

/*
|--------------------------------------------------------------------------
| Test 2: User Login
|--------------------------------------------------------------------------
*/
$loginResponse = sendRequest("POST", "/login", [
    "email" => $testEmail,
    "password" => $testPassword
]);

assertTest(
    isset($loginResponse["success"]) && $loginResponse["success"] === true,
    "User login should succeed with valid credentials"
);

if (isset($loginResponse["user"]["id"])) {
    $testUserId = $loginResponse["user"]["id"];
}

/*
|--------------------------------------------------------------------------
| Test 3: Get Courses
|--------------------------------------------------------------------------
*/
$coursesResponse = sendRequest("GET", "/courses");

assertTest(
    is_array($coursesResponse),
    "Courses endpoint should return a valid response"
);

if (isset($coursesResponse["data"][0]["id"])) {
    $testCourseId = $coursesResponse["data"][0]["id"];
} elseif (isset($coursesResponse[0]["id"])) {
    $testCourseId = $coursesResponse[0]["id"];
}

/*
|--------------------------------------------------------------------------
| Test 4: Course Enrollment
|--------------------------------------------------------------------------
*/
$enrollmentResponse = sendRequest("POST", "/enrollments", [
    "user_id" => $testUserId,
    "course_id" => $testCourseId
]);

assertTest(
    isset($enrollmentResponse["message"]),
    "Student should be able to enroll in a course"
);

/*
|--------------------------------------------------------------------------
| Test 5: Add Course Review
|--------------------------------------------------------------------------
*/
$reviewResponse = sendRequest("POST", "/reviews", [
    "user_id" => $testUserId,
    "course_id" => $testCourseId,
    "rating" => 5,
    "comment" => "This is an automated test review."
]);

assertTest(
    isset($reviewResponse["message"]) || isset($reviewResponse["success"]),
    "Student should be able to leave a course review"
);

/*
|--------------------------------------------------------------------------
| Test 6: Get User Notifications
|--------------------------------------------------------------------------
*/
$notificationResponse = sendRequest("GET", "/notifications/user/" . $testUserId);

assertTest(
    is_array($notificationResponse),
    "User notifications endpoint should return a valid response"
);

echo "-----------------------------" . PHP_EOL;
echo "Testing finished." . PHP_EOL;