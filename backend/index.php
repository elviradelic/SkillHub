<?php

$allowedOrigins = [
    "http://localhost:5173",
    "https://exquisite-pavlova-9f0dea.netlify.app",
    "https://6a2450ef8d2eef9b908c92f2--exquisite-pavlova-9f0dea.netlify.app"
];

$origin = $_SERVER["HTTP_ORIGIN"] ?? "";

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: *");
}

header("Vary: Origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Max-Age: 86400");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
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
require_once __DIR__ . '/controllers/ProgressController.php';
require_once __DIR__ . '/controllers/CertificateController.php';
require_once __DIR__ . '/controllers/LastAccessedLessonController.php';
require_once __DIR__ . '/controllers/NotificationController.php';
require_once __DIR__ . '/controllers/MaterialController.php';

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
$progressController = new ProgressController();
$certificateController = new CertificateController();
$lastAccessedLessonController = new LastAccessedLessonController();
$notificationController = new NotificationController();
$materialController = new MaterialController();

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

if (preg_match('#^/users/(\d+)/promote$#', $uri, $matches) && $method === 'PUT') {
    $userController->promoteToInstructor($matches[1]);
    exit;
}

if (preg_match('#^/users/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $userController->deleteUser($matches[1]);
    exit;
}

if ($uri === '/courses' && $method === 'GET') {
    $courseController->getCourses();
    exit;
}

if (preg_match('#^/instructor-courses/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $courseController->getInstructorCourses($matches[1]);
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

if (preg_match('#^/lessons/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    $lessonController->updateLesson($matches[1]);
    exit;
}

if (preg_match('#^/lessons/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $lessonController->deleteLesson($matches[1]);
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
    $enrollmentController->getUserEnrollments($matches[1]);
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

if ($uri === '/quizzes' && $method === 'POST') {
    $quizController->createQuiz();
    exit;
}

if ($uri === '/questions' && $method === 'POST') {
    $quizController->createQuestion();
    exit;
}

if ($uri === '/quiz-submit' && $method === 'POST') {
    $quizController->submitQuizWithAnswers();
    exit;
}

if ($uri === '/progress' && $method === 'POST') {
    $progressController->markLessonCompleted();
    exit;
}

if (preg_match('#^/progress/(\d+)/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $progressController->getCourseProgress($matches[1], $matches[2]);
    exit;
}

if ($uri === '/certificates' && $method === 'POST') {
    $certificateController->createCertificate();
    exit;
}

if (preg_match('#^/certificates/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $certificateController->getUserCertificates($matches[1]);
    exit;
}

if ($uri === '/last-accessed-lesson' && $method === 'POST') {
    $lastAccessedLessonController->saveLastAccessedLesson();
    exit;
}

if (preg_match('#^/last-accessed-lesson/(\d+)/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $lastAccessedLessonController->getLastAccessedLesson($matches[1], $matches[2]);
    exit;
}

if ($uri === '/notifications' && $method === 'POST') {
    $notificationController->createNotification();
    exit;
}

if (preg_match('#^/notifications/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $notificationController->getUserNotifications($matches[1]);
    exit;
}

if ($uri === '/materials' && $method === 'POST') {
    $materialController->uploadMaterial();
    exit;
}

if (preg_match('#^/materials/course/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $materialController->getMaterialsByCourse($matches[1]);
    exit;
}

if (
    preg_match('#^/materials/download/(\d+)$#', $uri, $matches)
    && $method === 'GET'
) {
    $materialController->downloadMaterial($matches[1]);
    exit;
}

http_response_code(404);
echo json_encode([
    "message" => "Route not found"
]);