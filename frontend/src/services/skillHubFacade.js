import API from "./api";

const skillHubFacade = {

  registerUser(data) {
    return API.post("/users", data);
  },

  getCourses() {
    return API.get("/courses");
  },

  getCourseById(id) {
    return API.get(`/courses/${id}`);
  },

  getLessonsByCourse(courseId) {
    return API.get(`/courses/${courseId}/lessons`);
  },

  enrollInCourse(data) {
    return API.post("/enrollments", data);
  },

  getCategories() {
    return API.get("/categories");
  },
};

export default skillHubFacade;