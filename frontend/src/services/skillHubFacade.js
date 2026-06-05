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

  getInstructorCourses(instructorId) {
    return API.get(`/instructor-courses/${instructorId}`);
  },

  createCourse(data) {
    return API.post("/courses", data);
  },

  updateCourse(id, data) {
    return API.put(`/courses/${id}`, data);
  },

  deleteCourse(id) {
    return API.delete(`/courses/${id}`);
  },

  getCategories() {
    return API.get("/categories");
  },

  getLessonsByCourse(courseId) {
    return API.get(`/courses/${courseId}/lessons`);
  },

  createLesson(data) {
    return API.post("/lessons", data);
  },

  updateLesson(id, data) {
    return API.put(`/lessons/${id}`, data);
  },

  deleteLesson(id) {
    return API.delete(`/lessons/${id}`);
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

  createQuiz(data) {
    return API.post("/quizzes", data);
  },

  getQuizQuestions(quizId) {
    return API.get(`/quizzes/${quizId}/questions`);
  },

  createQuestion(data) {
    return API.post("/questions", data);
  },

  submitQuizResult(data) {
    return API.post("/results", data);
  },

  submitQuizWithAnswers(data) {
    return API.post("/quiz-submit", data);
  },

  getUserResults(userId) {
    return API.get(`/results/user/${userId}`);
  },
  markLessonCompleted(data) {
  return API.post("/progress", data);
},

getCourseProgress(userId, courseId) {
  return API.get(`/progress/${userId}/${courseId}`);
},
createCertificate(data) {
  return API.post("/certificates", data);
},

getUserCertificates(userId) {
  return API.get(`/certificates/user/${userId}`);
},
getUsers() {
  return API.get("/users");
},

deleteUser(id) {
  return API.delete(`/users/${id}`);
},
saveLastAccessedLesson(data) {
  return API.post("/last-accessed-lesson", data);
},

getLastAccessedLesson(userId, courseId) {
  return API.get(`/last-accessed-lesson/${userId}/${courseId}`);
},
createNotification(data) {
  return API.post("/notifications", data);
},

getUserNotifications(userId) {
  return API.get(`/notifications/user/${userId}`);
},
uploadMaterial(data) {
  return API.post("/materials", data, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
  });
},

getMaterialsByCourse(courseId) {
  return API.get(`/materials/course/${courseId}`);
},
promoteToInstructor(id) {
  return API.put(`/users/${id}/promote`);
},

};

export default skillHubFacade;