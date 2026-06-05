import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./InstructorDashboard.css";

function InstructorDashboard() {
  const user = JSON.parse(localStorage.getItem("user"));

  const [courses, setCourses] = useState([]);
  const [editingCourseId, setEditingCourseId] = useState(null);
  const [categories, setCategories] = useState([]);

  const [activeLessonCourseId, setActiveLessonCourseId] = useState(null);
  const [activeQuizCourseId, setActiveQuizCourseId] = useState(null);
  const [activeQuestionQuizId, setActiveQuestionQuizId] = useState(null);
  const [activeMaterialCourseId, setActiveMaterialCourseId] = useState(null);

  const [courseData, setCourseData] = useState({
    title: "",
    description: "",
    price: "",
    category_id: "",
  });

  const [lessonData, setLessonData] = useState({
    title: "",
    content: "",
    lesson_order: "",
  });

  const [quizData, setQuizData] = useState({
    title: "",
    duration: "",
  });

  const [questionData, setQuestionData] = useState({
    text: "",
    points: "",
    correct_answer: "",
  });

  const [materialData, setMaterialData] = useState({
    title: "",
    file: null,
  });

  const notifyInstructor = (message) => {
    if (!user) return;

    skillHubFacade
      .createNotification({
        user_id: user.id,
        message,
      })
      .catch(() => {});
  };

  const loadCourses = () => {
    skillHubFacade
      .getInstructorCourses(user.id)
      .then((res) => setCourses(res.data.data || []))
      .catch(() => alert("Failed to load instructor courses."));
  };

  const loadCategories = () => {
    skillHubFacade
      .getCategories()
      .then((res) => setCategories(res.data || []))
      .catch(() => alert("Failed to load categories."));
  };

  useEffect(() => {
    loadCourses();
    loadCategories();
  }, []);

  const handleCourseChange = (e) => {
    setCourseData({ ...courseData, [e.target.name]: e.target.value });
  };

  const handleLessonChange = (e) => {
    setLessonData({ ...lessonData, [e.target.name]: e.target.value });
  };

  const handleQuizChange = (e) => {
    setQuizData({ ...quizData, [e.target.name]: e.target.value });
  };

  const handleQuestionChange = (e) => {
    setQuestionData({ ...questionData, [e.target.name]: e.target.value });
  };

  const handleMaterialChange = (e) => {
    setMaterialData({ ...materialData, [e.target.name]: e.target.value });
  };

  const handleMaterialFileChange = (e) => {
    setMaterialData({ ...materialData, file: e.target.files[0] });
  };

  const resetCourseForm = () => {
    setCourseData({
      title: "",
      description: "",
      price: "",
      category_id: "",
    });
    setEditingCourseId(null);
  };

  const resetLessonForm = () => {
    setLessonData({
      title: "",
      content: "",
      lesson_order: "",
    });
    setActiveLessonCourseId(null);
  };

  const resetQuizForm = () => {
    setQuizData({
      title: "",
      duration: "",
    });
    setActiveQuizCourseId(null);
  };

  const resetQuestionForm = () => {
    setQuestionData({
      text: "",
      points: "",
      correct_answer: "",
    });
    setActiveQuestionQuizId(null);
  };

  const resetMaterialForm = () => {
    setMaterialData({
      title: "",
      file: null,
    });
    setActiveMaterialCourseId(null);
  };

  const handleCreateOrUpdateCourse = (e) => {
    e.preventDefault();

    const payload = {
      ...courseData,
      instructor_id: user.id,
    };

    const request = editingCourseId
      ? skillHubFacade.updateCourse(editingCourseId, payload)
      : skillHubFacade.createCourse(payload);

    request
      .then(() => {
        alert(
          editingCourseId
            ? "Course updated successfully."
            : "Course created successfully."
        );

        notifyInstructor(
          editingCourseId
            ? `Course "${courseData.title}" was updated.`
            : `Course "${courseData.title}" was created.`
        );

        resetCourseForm();
        loadCourses();
      })
      .catch(() => {
        alert(
          editingCourseId
            ? "Failed to update course."
            : "Failed to create course."
        );
      });
  };

  const handleEditCourse = (course) => {
    setEditingCourseId(course.id);
    setCourseData({
      title: course.title,
      description: course.description,
      price: course.price,
      category_id: course.category_id,
    });

    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const handleDeleteCourse = (courseId) => {
    if (!window.confirm("Are you sure you want to delete this course?")) return;

    const deletedCourse = courses.find((course) => course.id === courseId);

    skillHubFacade
      .deleteCourse(courseId)
      .then(() => {
        alert("Course deleted successfully.");

        notifyInstructor(
          deletedCourse
            ? `Course "${deletedCourse.title}" was deleted.`
            : "Course was deleted."
        );

        loadCourses();
      })
      .catch(() => alert("Failed to delete course."));
  };

  const handleAddLesson = (e, courseId) => {
    e.preventDefault();

    const currentCourse = courses.find((course) => course.id === courseId);

    skillHubFacade
      .createLesson({
        course_id: courseId,
        title: lessonData.title,
        content: lessonData.content,
        lesson_order: lessonData.lesson_order,
      })
      .then(() => {
        alert("Lesson added successfully.");

        notifyInstructor(
          currentCourse
            ? `Lesson "${lessonData.title}" was added to "${currentCourse.title}".`
            : `Lesson "${lessonData.title}" was added.`
        );

        resetLessonForm();
      })
      .catch(() => alert("Failed to add lesson."));
  };

  const handleCreateQuiz = (e, courseId) => {
    e.preventDefault();

    const currentCourse = courses.find((course) => course.id === courseId);

    skillHubFacade
      .createQuiz({
        course_id: courseId,
        title: quizData.title,
        duration: quizData.duration,
      })
      .then((res) => {
        alert("Quiz created successfully.");

        notifyInstructor(
          currentCourse
            ? `Quiz "${quizData.title}" was created for "${currentCourse.title}".`
            : `Quiz "${quizData.title}" was created.`
        );

        setActiveQuestionQuizId(res.data.id);
        resetQuizForm();
      })
      .catch(() => alert("Failed to create quiz."));
  };

  const handleAddQuestion = (e) => {
    e.preventDefault();

    skillHubFacade
      .createQuestion({
        quiz_id: activeQuestionQuizId,
        text: questionData.text,
        points: questionData.points,
        correct_answer: questionData.correct_answer,
      })
      .then(() => {
        alert("Question added successfully.");
        notifyInstructor("A new quiz question was added.");

        setQuestionData({
          text: "",
          points: "",
          correct_answer: "",
        });
      })
      .catch(() => alert("Failed to add question."));
  };

  const handleUploadMaterial = (e, courseId) => {
    e.preventDefault();

    if (!materialData.file) {
      alert("Please choose a file.");
      return;
    }

    const currentCourse = courses.find((course) => course.id === courseId);

    const formData = new FormData();
    formData.append("course_id", courseId);
    formData.append("title", materialData.title);
    formData.append("file", materialData.file);

    skillHubFacade
      .uploadMaterial(formData)
      .then(() => {
        alert("Material uploaded successfully.");

        notifyInstructor(
          currentCourse
            ? `Material "${materialData.title}" was uploaded for "${currentCourse.title}".`
            : `Material "${materialData.title}" was uploaded.`
        );

        resetMaterialForm();
      })
      .catch(() => {
        alert("Failed to upload material.");
      });
  };

  return (
    <div className="instructor-page">
      <div className="instructor-hero">
        <h1>Instructor Dashboard</h1>
        <p>
          Create courses, manage lessons, upload materials, and prepare quizzes
          for your students.
        </p>
      </div>

      <section className="instructor-panel">
        <h2>{editingCourseId ? "Edit Course" : "Create Course"}</h2>

        <form className="instructor-form" onSubmit={handleCreateOrUpdateCourse}>
          <input
            type="text"
            name="title"
            placeholder="Course title"
            value={courseData.title}
            onChange={handleCourseChange}
            required
          />

          <textarea
            name="description"
            placeholder="Course description"
            value={courseData.description}
            onChange={handleCourseChange}
            required
          />

          <input
            type="number"
            name="price"
            placeholder="Price"
            value={courseData.price}
            onChange={handleCourseChange}
            required
          />

          <select
            name="category_id"
            value={courseData.category_id}
            onChange={handleCourseChange}
            required
          >
            <option value="">Select category</option>
            {categories.map((category) => (
              <option key={category.id} value={category.id}>
                {category.name}
              </option>
            ))}
          </select>

          <div className="instructor-actions">
            <button className="instructor-btn instructor-btn-primary" type="submit">
              {editingCourseId ? "Update Course" : "Create Course"}
            </button>

            {editingCourseId && (
              <button
                className="instructor-btn instructor-btn-secondary"
                type="button"
                onClick={resetCourseForm}
              >
                Cancel Edit
              </button>
            )}
          </div>
        </form>
      </section>

      <section className="instructor-panel">
        <h2>My Courses</h2>

        {courses.length === 0 ? (
          <p>You have not created any courses yet.</p>
        ) : (
          <div className="instructor-grid">
            {courses.map((course) => (
              <div key={course.id} className="instructor-card">
                <h3>{course.title}</h3>

                <p>{course.description}</p>

                <div className="instructor-meta">
                  <span>Price: ${course.price}</span>
                  <span>Category: {course.category_name}</span>
                </div>

                <div className="instructor-actions">
                  <button
                    className="instructor-btn instructor-btn-primary"
                    onClick={() => handleEditCourse(course)}
                  >
                    Edit
                  </button>

                  <button
                    className="instructor-btn instructor-btn-danger"
                    onClick={() => handleDeleteCourse(course.id)}
                  >
                    Delete
                  </button>

                  <button
                    className="instructor-btn instructor-btn-secondary"
                    onClick={() => setActiveLessonCourseId(course.id)}
                  >
                    Add Lesson
                  </button>

                  <button
                    className="instructor-btn instructor-btn-secondary"
                    onClick={() => setActiveQuizCourseId(course.id)}
                  >
                    Create Quiz
                  </button>

                  <button
                    className="instructor-btn instructor-btn-success"
                    onClick={() => setActiveMaterialCourseId(course.id)}
                  >
                    Upload Material
                  </button>
                </div>

                {activeLessonCourseId === course.id && (
                  <form
                    className="instructor-subform instructor-form"
                    onSubmit={(e) => handleAddLesson(e, course.id)}
                  >
                    <h4>Add Lesson</h4>

                    <input
                      type="text"
                      name="title"
                      placeholder="Lesson title"
                      value={lessonData.title}
                      onChange={handleLessonChange}
                      required
                    />

                    <textarea
                      name="content"
                      placeholder="Lesson content"
                      value={lessonData.content}
                      onChange={handleLessonChange}
                      required
                    />

                    <input
                      type="number"
                      name="lesson_order"
                      placeholder="Lesson order"
                      value={lessonData.lesson_order}
                      onChange={handleLessonChange}
                      required
                    />

                    <div className="instructor-actions">
                      <button className="instructor-btn instructor-btn-primary" type="submit">
                        Save Lesson
                      </button>

                      <button
                        className="instructor-btn instructor-btn-secondary"
                        type="button"
                        onClick={resetLessonForm}
                      >
                        Cancel
                      </button>
                    </div>
                  </form>
                )}

                {activeQuizCourseId === course.id && (
                  <form
                    className="instructor-subform instructor-form"
                    onSubmit={(e) => handleCreateQuiz(e, course.id)}
                  >
                    <h4>Create Quiz</h4>

                    <input
                      type="text"
                      name="title"
                      placeholder="Quiz title"
                      value={quizData.title}
                      onChange={handleQuizChange}
                      required
                    />

                    <input
                      type="number"
                      name="duration"
                      placeholder="Duration in minutes"
                      value={quizData.duration}
                      onChange={handleQuizChange}
                      required
                    />

                    <div className="instructor-actions">
                      <button className="instructor-btn instructor-btn-primary" type="submit">
                        Save Quiz
                      </button>

                      <button
                        className="instructor-btn instructor-btn-secondary"
                        type="button"
                        onClick={resetQuizForm}
                      >
                        Cancel
                      </button>
                    </div>
                  </form>
                )}

                {activeMaterialCourseId === course.id && (
                  <form
                    className="instructor-subform instructor-form"
                    onSubmit={(e) => handleUploadMaterial(e, course.id)}
                  >
                    <h4>Upload Material</h4>

                    <input
                      type="text"
                      name="title"
                      placeholder="Material title"
                      value={materialData.title}
                      onChange={handleMaterialChange}
                      required
                    />

                    <input
                      type="file"
                      onChange={handleMaterialFileChange}
                      required
                    />

                    <div className="instructor-actions">
                      <button className="instructor-btn instructor-btn-primary" type="submit">
                        Upload Material
                      </button>

                      <button
                        className="instructor-btn instructor-btn-secondary"
                        type="button"
                        onClick={resetMaterialForm}
                      >
                        Cancel
                      </button>
                    </div>
                  </form>
                )}

                {activeQuestionQuizId && activeQuizCourseId === null && (
                  <form
                    className="instructor-subform instructor-form"
                    onSubmit={handleAddQuestion}
                  >
                    <h4>Add Question to Created Quiz</h4>

                    <input
                      type="text"
                      name="text"
                      placeholder="Question text"
                      value={questionData.text}
                      onChange={handleQuestionChange}
                      required
                    />

                    <input
                      type="number"
                      name="points"
                      placeholder="Points"
                      value={questionData.points}
                      onChange={handleQuestionChange}
                      required
                    />

                    <input
                      type="text"
                      name="correct_answer"
                      placeholder="Correct answer"
                      value={questionData.correct_answer}
                      onChange={handleQuestionChange}
                      required
                    />

                    <div className="instructor-actions">
                      <button className="instructor-btn instructor-btn-primary" type="submit">
                        Add Question
                      </button>

                      <button
                        className="instructor-btn instructor-btn-secondary"
                        type="button"
                        onClick={resetQuestionForm}
                      >
                        Finish Questions
                      </button>
                    </div>
                  </form>
                )}
              </div>
            ))}
          </div>
        )}
      </section>
    </div>
  );
}

export default InstructorDashboard;