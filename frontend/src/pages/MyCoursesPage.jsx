import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./MyCoursesPage.css";

function MyCoursesPage() {
  const [courses, setCourses] = useState([]);
  const [courseDetails, setCourseDetails] = useState({});
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
        const enrolledCourses = res.data.data || [];
        setCourses(enrolledCourses);

        const detailRequests = enrolledCourses.map((course) =>
          Promise.all([
            skillHubFacade.getCourseProgress(user.id, course.course_id),
            skillHubFacade.getLastAccessedLesson(user.id, course.course_id),
          ]).then(([progressRes, lastLessonRes]) => ({
            courseId: course.course_id,
            progress: progressRes.data.data,
            lastLesson: lastLessonRes.data.data,
          }))
        );

        return Promise.all(detailRequests);
      })
      .then((details) => {
        const detailsMap = {};

        details.forEach((item) => {
          detailsMap[item.courseId] = {
            progress: item.progress,
            lastLesson: item.lastLesson,
          };
        });

        setCourseDetails(detailsMap);
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
          {courses.map((course) => {
            const details = courseDetails[course.course_id];
            const progress = details?.progress;
            const lastLesson = details?.lastLesson;

            return (
              <div key={course.enrollment_id} className="my-course-card">
                <h2>{course.title}</h2>
                <p>{course.description}</p>
                <p className="course-status">Status: {course.status}</p>

                {progress && (
                  <div style={{ marginTop: "10px" }}>
                    <p>
                      Progress: {progress.completed_lessons} /{" "}
                      {progress.total_lessons} lessons completed
                    </p>

                    <div
                      style={{
                        width: "100%",
                        height: "15px",
                        background: "#eee",
                        borderRadius: "8px",
                      }}
                    >
                      <div
                        style={{
                          width: `${progress.progress_percentage}%`,
                          height: "100%",
                          background: "#7c3aed",
                          borderRadius: "8px",
                        }}
                      />
                    </div>

                    <p>{progress.progress_percentage}% completed</p>
                  </div>
                )}

                {lastLesson && (
                  <div
                    style={{
                      marginTop: "10px",
                      padding: "10px",
                      background: "#f5f3ff",
                      borderRadius: "8px",
                    }}
                  >
                    <strong>Last lesson:</strong> {lastLesson.title}
                  </div>
                )}

                <a href={`/courses/${course.course_id}`} className="btn-continue">
                  Continue Learning
                </a>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}

export default MyCoursesPage;