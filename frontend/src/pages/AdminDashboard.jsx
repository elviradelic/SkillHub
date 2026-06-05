import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./AdminDashboard.css";

function AdminDashboard() {
  const user = JSON.parse(localStorage.getItem("user"));

  const [users, setUsers] = useState([]);
  const [courses, setCourses] = useState([]);
  const [editingCourseId, setEditingCourseId] = useState(null);

  const [courseData, setCourseData] = useState({
    title: "",
    description: "",
    price: "",
    instructor_id: "",
    category_id: "",
  });

  const loadUsers = () => {
    skillHubFacade
      .getUsers()
      .then((res) => setUsers(res.data || []))
      .catch(() => alert("Failed to load users."));
  };

  const loadCourses = () => {
    skillHubFacade
      .getCourses()
      .then((res) => setCourses(res.data || []))
      .catch(() => alert("Failed to load courses."));
  };

  useEffect(() => {
    loadUsers();
    loadCourses();
  }, []);

  const handleCourseChange = (e) => {
    setCourseData({
      ...courseData,
      [e.target.name]: e.target.value,
    });
  };

  const resetCourseForm = () => {
    setEditingCourseId(null);
    setCourseData({
      title: "",
      description: "",
      price: "",
      instructor_id: "",
      category_id: "",
    });
  };

  const handleEditCourse = (course) => {
    setEditingCourseId(course.id);
    setCourseData({
      title: course.title,
      description: course.description,
      price: course.price,
      instructor_id: course.instructor_id,
      category_id: course.category_id,
    });

    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const handleUpdateCourse = (e) => {
    e.preventDefault();

    skillHubFacade
      .updateCourse(editingCourseId, courseData)
      .then(() => {
        alert("Course updated successfully.");
        resetCourseForm();
        loadCourses();
      })
      .catch(() => alert("Failed to update course."));
  };

  const handleDeleteUser = (userId) => {
    if (!window.confirm("Are you sure you want to delete this user?")) return;

    skillHubFacade
      .deleteUser(userId)
      .then(() => {
        alert("User deleted successfully.");
        loadUsers();
      })
      .catch(() => alert("Failed to delete user."));
  };

  const handlePromoteUser = (userId) => {
    if (!window.confirm("Promote this user to instructor?")) return;

    skillHubFacade
      .promoteToInstructor(userId)
      .then(() => {
        alert("User promoted to instructor successfully.");
        loadUsers();
      })
      .catch(() => alert("Failed to promote user."));
  };

  const handleDeleteCourse = (courseId) => {
    if (!window.confirm("Are you sure you want to delete this course?")) return;

    skillHubFacade
      .deleteCourse(courseId)
      .then(() => {
        alert("Course deleted successfully.");
        loadCourses();
      })
      .catch(() => alert("Failed to delete course."));
  };

  if (!user || user.role !== "admin") {
    return <p className="admin-access-denied">Access denied. Admins only.</p>;
  }

  const totalStudents = users.filter((item) => item.role === "student").length;
  const totalInstructors = users.filter(
    (item) => item.role === "instructor"
  ).length;

  return (
    <div className="admin-page">
      <div className="admin-hero">
        <div>
          <p className="admin-kicker">SkillHub Control Panel</p>
          <h1>Admin Dashboard</h1>
          <p>
            Manage platform users, approve instructors, and maintain course
            quality from one place.
          </p>
        </div>
      </div>

      <div className="admin-stats">
        <div className="admin-stat-card">
          <span>Total Users</span>
          <strong>{users.length}</strong>
        </div>

        <div className="admin-stat-card">
          <span>Students</span>
          <strong>{totalStudents}</strong>
        </div>

        <div className="admin-stat-card">
          <span>Instructors</span>
          <strong>{totalInstructors}</strong>
        </div>

        <div className="admin-stat-card">
          <span>Courses</span>
          <strong>{courses.length}</strong>
        </div>
      </div>

      {editingCourseId && (
        <section className="admin-panel admin-edit-panel">
          <div className="admin-section-header">
            <h2>Edit Course</h2>
            <p>Update course information and save changes.</p>
          </div>

          <form className="admin-form" onSubmit={handleUpdateCourse}>
            <div className="admin-form-grid">
              <div className="admin-form-group">
                <label>Course Title</label>
                <input
                  type="text"
                  name="title"
                  value={courseData.title}
                  onChange={handleCourseChange}
                  required
                />
              </div>

              <div className="admin-form-group">
                <label>Price</label>
                <input
                  type="number"
                  name="price"
                  value={courseData.price}
                  onChange={handleCourseChange}
                  required
                />
              </div>

              <div className="admin-form-group">
                <label>Instructor ID</label>
                <input
                  type="number"
                  name="instructor_id"
                  value={courseData.instructor_id}
                  onChange={handleCourseChange}
                  required
                />
              </div>

              <div className="admin-form-group">
                <label>Category ID</label>
                <input
                  type="number"
                  name="category_id"
                  value={courseData.category_id}
                  onChange={handleCourseChange}
                  required
                />
              </div>
            </div>

            <div className="admin-form-group">
              <label>Description</label>
              <textarea
                name="description"
                value={courseData.description}
                onChange={handleCourseChange}
                required
              />
            </div>

            <div className="admin-actions">
              <button className="admin-btn admin-btn-primary" type="submit">
                Update Course
              </button>

              <button
                className="admin-btn admin-btn-secondary"
                type="button"
                onClick={resetCourseForm}
              >
                Cancel
              </button>
            </div>
          </form>
        </section>
      )}

      <section className="admin-panel">
        <div className="admin-section-header">
          <h2>Manage Users</h2>
          <p>View users, remove accounts, or promote students to instructors.</p>
        </div>

        {users.length === 0 ? (
          <p className="admin-empty">No users found.</p>
        ) : (
          <div className="admin-grid">
            {users.map((item) => (
              <div key={item.id} className="admin-card">
                <div className="admin-card-top">
                  <div>
                    <h3>{item.name}</h3>
                    <p>{item.email}</p>
                  </div>

                  <span className={`admin-role admin-role-${item.role}`}>
                    {item.role}
                  </span>
                </div>

                <div className="admin-card-actions">
                  {item.role === "student" && (
                    <button
                      className="admin-btn admin-btn-success"
                      onClick={() => handlePromoteUser(item.id)}
                    >
                      Promote
                    </button>
                  )}

                  <button
                    className="admin-btn admin-btn-danger"
                    onClick={() => handleDeleteUser(item.id)}
                    disabled={item.id === user.id}
                  >
                    Delete
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </section>

      <section className="admin-panel">
        <div className="admin-section-header">
          <h2>Manage Courses</h2>
          <p>Edit or delete courses to keep the platform content organized.</p>
        </div>

        {courses.length === 0 ? (
          <p className="admin-empty">No courses found.</p>
        ) : (
          <div className="admin-grid">
            {courses.map((course) => (
              <div key={course.id} className="admin-card">
                <div className="admin-card-top">
                  <div>
                    <h3>{course.title}</h3>
                    <p>{course.description}</p>
                  </div>
                </div>

                <div className="admin-meta">
                  <span>Price: ${course.price}</span>
                  <span>Category: {course.category_name}</span>
                  <span>Instructor: {course.instructor_name}</span>
                </div>

                <div className="admin-card-actions">
                  <button
                    className="admin-btn admin-btn-primary"
                    onClick={() => handleEditCourse(course)}
                  >
                    Edit
                  </button>

                  <button
                    className="admin-btn admin-btn-danger"
                    onClick={() => handleDeleteCourse(course.id)}
                  >
                    Delete
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </section>
    </div>
  );
}

export default AdminDashboard;