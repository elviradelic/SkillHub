<?php

header("Content-Type: application/json");

require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CourseController.php';
require_once __DIR__ . '/controllers/EnrollmentController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/LessonController.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$userController = new UserController();
$courseController = new CourseController();
$enrollmentController = new EnrollmentController();
$categoryController = new CategoryController();
$lessonController = new LessonController();

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

echo json_encode([
    "message" => "Route not found"
]);