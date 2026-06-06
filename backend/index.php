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

if ($uri === '/') {
    echo json_encode([
        "message" => "SkillHub API is running"
    ]);
    exit;
}

if ($uri === '/users' && $method === 'GET') {
    (new UserController())->getUsers();
    exit;
}

if ($uri === '/users' && $method === 'POST') {
    (new UserController())->createUser();
    exit;
}

if (preg_match('#^/users/(\d+)/promote$#', $uri, $matches) && $method === 'PUT') {
    (new UserController())->promoteToInstructor($matches[1]);
    exit;
}

if (preg_match('#^/users/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    (new UserController())->deleteUser($matches[1]);
    exit;
}

if ($uri === '/courses' && $method === 'GET') {
    (new CourseController())->getCourses();
    exit;
}

if (preg_match('#^/instructor-courses/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new CourseController())->getInstructorCourses($matches[1]);
    exit;
}

if ($uri === '/courses' && $method === 'POST') {
    (new CourseController())->createCourse();
    exit;
}

if (preg_match('/^\/courses\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    (new CourseController())->getCourseById($matches[1]);
    exit;
}

if (preg_match('/^\/courses\/(\d+)$/', $uri, $matches) && $method === 'PUT') {
    (new CourseController())->updateCourse($matches[1]);
    exit;
}

if (preg_match('/^\/courses\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    (new CourseController())->deleteCourse($matches[1]);
    exit;
}

if ($uri === '/enrollments' && $method === 'GET') {
    (new EnrollmentController())->getEnrollments();
    exit;
}

if ($uri === '/enrollments' && $method === 'POST') {
    (new EnrollmentController())->createEnrollment();
    exit;
}

if ($uri === '/categories' && $method === 'GET') {
    (new CategoryController())->getCategories();
    exit;
}

if ($uri === '/categories' && $method === 'POST') {
    (new CategoryController())->createCategory();
    exit;
}

if ($uri === '/lessons' && $method === 'GET') {
    (new LessonController())->getLessons();
    exit;
}

if ($uri === '/lessons' && $method === 'POST') {
    (new LessonController())->createLesson();
    exit;
}

if (preg_match('#^/lessons/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    (new LessonController())->updateLesson($matches[1]);
    exit;
}

if (preg_match('#^/lessons/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    (new LessonController())->deleteLesson($matches[1]);
    exit;
}

if (preg_match('/^\/courses\/(\d+)\/lessons$/', $uri, $matches) && $method === 'GET') {
    (new LessonController())->getLessonsByCourse($matches[1]);
    exit;
}

if ($uri === '/login' && $method === 'POST') {
    (new AuthController())->login();
    exit;
}

if (preg_match('#^/my-courses/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new EnrollmentController())->getUserEnrollments($matches[1]);
    exit;
}

if ($uri === '/reviews' && $method === 'POST') {
    (new ReviewController())->createReview();
    exit;
}

if (preg_match('#^/reviews/course/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new ReviewController())->getCourseReviews($matches[1]);
    exit;
}

if (preg_match('#^/quizzes/course/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new QuizController())->getQuizByCourse($matches[1]);
    exit;
}

if (preg_match('#^/quizzes/(\d+)/questions$#', $uri, $matches) && $method === 'GET') {
    (new QuizController())->getQuizQuestions($matches[1]);
    exit;
}

if ($uri === '/results' && $method === 'POST') {
    (new QuizController())->submitQuizResult();
    exit;
}

if (preg_match('#^/results/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new QuizController())->getUserResults($matches[1]);
    exit;
}

if ($uri === '/quizzes' && $method === 'POST') {
    (new QuizController())->createQuiz();
    exit;
}

if ($uri === '/questions' && $method === 'POST') {
    (new QuizController())->createQuestion();
    exit;
}

if ($uri === '/quiz-submit' && $method === 'POST') {
    (new QuizController())->submitQuizWithAnswers();
    exit;
}

if ($uri === '/progress' && $method === 'POST') {
    (new ProgressController())->markLessonCompleted();
    exit;
}

if (preg_match('#^/progress/(\d+)/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new ProgressController())->getCourseProgress($matches[1], $matches[2]);
    exit;
}

if ($uri === '/certificates' && $method === 'POST') {
    (new CertificateController())->createCertificate();
    exit;
}

if (preg_match('#^/certificates/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new CertificateController())->getUserCertificates($matches[1]);
    exit;
}

if ($uri === '/last-accessed-lesson' && $method === 'POST') {
    (new LastAccessedLessonController())->saveLastAccessedLesson();
    exit;
}

if (preg_match('#^/last-accessed-lesson/(\d+)/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new LastAccessedLessonController())->getLastAccessedLesson($matches[1], $matches[2]);
    exit;
}

if ($uri === '/notifications' && $method === 'POST') {
    (new NotificationController())->createNotification();
    exit;
}

if (preg_match('#^/notifications/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new NotificationController())->getUserNotifications($matches[1]);
    exit;
}

if ($uri === '/materials' && $method === 'POST') {
    (new MaterialController())->uploadMaterial();
    exit;
}

if (preg_match('#^/materials/course/(\d+)$#', $uri, $matches) && $method === 'GET') {
    (new MaterialController())->getMaterialsByCourse($matches[1]);
    exit;
}

if (
    preg_match('#^/materials/download/(\d+)$#', $uri, $matches)
    && $method === 'GET'
) {
    (new MaterialController())->downloadMaterial($matches[1]);
    exit;
}

http_response_code(404);
echo json_encode([
    "message" => "Route not found"
]);