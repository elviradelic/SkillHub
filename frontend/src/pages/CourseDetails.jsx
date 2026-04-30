import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./CourseDetails.css";

function CourseDetails() {
  const { id } = useParams();
  const [course, setCourse] = useState(null);
  const [lessons, setLessons] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [enrolled, setEnrolled] = useState(false);

  useEffect(() => {
    skillHubFacade
      .getCourseById(id)
      .then((res) => {
        setCourse(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load course.");
        setLoading(false);
      });

    skillHubFacade
      .getLessonsByCourse(id)
      .then((res) => {
        setLessons(res.data);
      })
      .catch(() => {
        setLessons([]);
      });
  }, [id]);

  const handleEnroll = () => {
    // TODO: replace 1 with logged-in user id when auth is implemented
    skillHubFacade
      .enrollInCourse({ user_id: 1, course_id: Number(id) })
      .then(() => {
        setEnrolled(true);
      })
      .catch(() => {
        alert("Enrollment failed. Please try again.");
      });
  };

  if (loading) return <p className="loading">Loading course...</p>;
  if (error) return <p className="error">{error}</p>;

  return (
    <div className="course-details">
      <div className="course-details-header">
        <h1>{course.title}</h1>
        <p>{course.description}</p>
        <p className="course-details-price">${course.price}</p>

        {enrolled ? (
          <p className="enrolled-msg">You are enrolled in this course!</p>
        ) : (
          <button className="btn-enroll" onClick={handleEnroll}>
            Enroll Now
          </button>
        )}
      </div>

      <div className="course-lessons">
        <h2>Lessons</h2>
        {lessons.length > 0 ? (
          <ul>
            {lessons.map((lesson) => (
              <li key={lesson.id}>{lesson.title}</li>
            ))}
          </ul>
        ) : (
          <p>No lessons available yet.</p>
        )}
      </div>
    </div>
  );
}

export default CourseDetails;