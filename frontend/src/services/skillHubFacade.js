import API from "./api";

const skillHubFacade = {
  registerUser(data) {
    return API.post("/users", data);
  },

  loginUser(data) {
    return API.post("/login", data);
  },

  getCourses() {
    return API.get("/courses");
  },

  getCourseById(id) {
    return API.get(`/courses/${id}`);
  },

  getCategories() {
    return API.get("/categories");
  },

  getLessonsByCourse(courseId) {
    return API.get(`/courses/${courseId}/lessons`);
  },

  enrollInCourse(data) {
    return API.post("/enrollments", data);
  },

  getMyCourses(userId) {
    return API.get(`/my-courses/${userId}`);
  },

  addReview(data) {
    return API.post("/reviews", data);
  },

  getCourseReviews(courseId) {
    return API.get(`/reviews/course/${courseId}`);
  },

  getQuizByCourse(courseId) {
    return API.get(`/quizzes/course/${courseId}`);
  },

  getQuizQuestions(quizId) {
    return API.get(`/quizzes/${quizId}/questions`);
  },

  submitQuizResult(data) {
    return API.post("/results", data);
  },

  getUserResults(userId) {
    return API.get(`/results/user/${userId}`);
  },
};

export default skillHubFacade;