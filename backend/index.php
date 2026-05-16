<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CourseController.php';
require_once __DIR__ . '/controllers/EnrollmentController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/LessonController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ReviewController.php';
require_once __DIR__ . '/controllers/QuizController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$userController = new UserController();
$courseController = new CourseController();
$enrollmentController = new EnrollmentController();
$categoryController = new CategoryController();
$lessonController = new LessonController();
$authController = new AuthController();
$reviewController = new ReviewController();
$quizController = new QuizController();

if ($uri === '/') {
    echo json_encode([
        "message" => "SkillHub API is running"
    ]);
    exit;
}

if ($uri === '/users' && $method === 'GET') {
    $userController->getUsers();
    exit;
}

if ($uri === '/users' && $method === 'POST') {
    $userController->createUser();
    exit;
}

if ($uri === '/courses' && $method === 'GET') {
    $courseController->getCourses();
    exit;
}

if ($uri === '/courses' && $method === 'POST') {
    $courseController->createCourse();
    exit;
}

if (preg_match('/^\/courses\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $courseController->getCourseById($matches[1]);
    exit;
}

if (preg_match('/^\/courses\/(\d+)$/', $uri, $matches) && $method === 'PUT') {
    $courseController->updateCourse($matches[1]);
    exit;
}

if (preg_match('/^\/courses\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    $courseController->deleteCourse($matches[1]);
    exit;
}

if ($uri === '/enrollments' && $method === 'GET') {
    $enrollmentController->getEnrollments();
    exit;
}

if ($uri === '/enrollments' && $method === 'POST') {
    $enrollmentController->createEnrollment();
    exit;
}

if ($uri === '/categories' && $method === 'GET') {
    $categoryController->getCategories();
    exit;
}

if ($uri === '/categories' && $method === 'POST') {
    $categoryController->createCategory();
    exit;
}

if ($uri === '/lessons' && $method === 'GET') {
    $lessonController->getLessons();
    exit;
}

if ($uri === '/lessons' && $method === 'POST') {
    $lessonController->createLesson();
    exit;
}

if (preg_match('/^\/courses\/(\d+)\/lessons$/', $uri, $matches) && $method === 'GET') {
    $lessonController->getLessonsByCourse($matches[1]);
    exit;
}
if ($uri === '/login' && $method === 'POST') {
    $authController->login();
    exit;
}
if (preg_match('#^/my-courses/(\d+)$#', $uri, $matches) && $method === 'GET') {

    $userId = $matches[1];

    $enrollmentController->getUserEnrollments($userId);

    exit;
}
if ($uri === '/reviews' && $method === 'POST') {
    $reviewController->createReview();
    exit;
}

if (preg_match('#^/reviews/course/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $reviewController->getCourseReviews($matches[1]);
    exit;
}
if (preg_match('#^/quizzes/course/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $quizController->getQuizByCourse($matches[1]);
    exit;
}

if (preg_match('#^/quizzes/(\d+)/questions$#', $uri, $matches) && $method === 'GET') {
    $quizController->getQuizQuestions($matches[1]);
    exit;
}

if ($uri === '/results' && $method === 'POST') {
    $quizController->submitQuizResult();
    exit;
}
if (preg_match('#^/results/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $quizController->getUserResults($matches[1]);
    exit;
}

http_response_code(404);
echo json_encode([
    "message" => "Route not found"
]);