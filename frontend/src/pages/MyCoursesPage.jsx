import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./MyCoursesPage.css";

function MyCoursesPage() {
  const [courses, setCourses] = useState([]);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(true);

  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    if (!user) {
      setError("Please log in to view your courses.");
      setLoading(false);
      return;
    }

    skillHubFacade
      .getMyCourses(user.id)
      .then((res) => {
        setCourses(res.data.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load enrolled courses.");
        setLoading(false);
      });
  }, []);

  if (loading) return <p className="loading">Loading...</p>;
  if (error) return <p className="error">{error}</p>;

  return (
    <div className="my-courses-page">
      <h1>My Courses</h1>

      {courses.length === 0 ? (
        <p>You are not enrolled in any courses yet.</p>
      ) : (
        <div className="my-courses-grid">
          {courses.map((course) => (
            <div key={course.id} className="my-course-card">
              <h2>{course.title}</h2>
              <p>{course.description}</p>
              <p className="course-status">Status: {course.status}</p>
              <a href={`/courses/${course.id}`} className="btn-continue">
                Continue Learning
              </a>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default MyCoursesPage;